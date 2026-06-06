<?php

namespace App\Http\Controllers;

use App\Models\LkeComponent;
use App\Models\LkeCriteria;
use App\Models\LkeEvaluation;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IsiPenilaianController extends Controller
{
    public function index()
    {
        $institutionId = Auth::user()->institution_id;
        $year = date('Y'); 

        // 1. Ambil data Batas Waktu pengerjaan dari database
        $deadlineSetting = DB::table('app_settings')->where('key', 'lke_deadline')->first();
        $deadline = $deadlineSetting ? $deadlineSetting->value : null;
        $isPastDeadline = $deadline ? (now() > \Carbon\Carbon::parse($deadline)) : false;

        $components = LkeComponent::with(['subComponents.criteria.evaluations' => function($query) use ($institutionId, $year) {
            $query->where('institution_id', $institutionId)
                  ->where('evaluation_year', $year)
                  ->with('documents'); 
        }])->get();

        $documents = Document::where('institution_id', $institutionId)->orderBy('created_at', 'desc')->get();

        $totalCriteria = LkeCriteria::count();
        $filledCriteria = LkeEvaluation::where('institution_id', $institutionId)
            ->where('evaluation_year', $year)
            ->whereNotNull('predicate')
            ->count();
            
        $progress = $totalCriteria > 0 ? round(($filledCriteria / $totalCriteria) * 100) : 0;

        // 2. Cek apakah status terkunci karena sedang diajukan ('submitted')
        $isSubmitted = LkeEvaluation::where('institution_id', $institutionId)
            ->where('evaluation_year', $year)
            ->where('status', 'submitted')
            ->exists();

        // 3. BARU: Cek apakah status dinas sudah disetujui/dikunci final oleh Inspektorat ('disetujui')
        // Jika ada minimal satu kriteria berstatus 'disetujui' dan tidak ada yang 'submitted' / 'revisi', berarti evaluasi selesai
        $hasApproved = LkeEvaluation::where('institution_id', $institutionId)->where('evaluation_year', $year)->where('status', 'disetujui')->exists();
        $hasAwaiting = LkeEvaluation::where('institution_id', $institutionId)->where('evaluation_year', $year)->whereIn('status', ['submitted', 'menunggu'])->exists();
        $isApproved = $hasApproved && !$hasAwaiting;

        // 4. CARI NAMA PEMERIKSA DARI INSPEKTORAT
        $pemeriksa = User::role('operator_inspektorat')
            ->whereHas('binaanInstitutions', function($q) use ($institutionId) {
                $q->where('institutions.id', $institutionId);
            })->first();
            
        $namaPemeriksa = $pemeriksa ? $pemeriksa->name : 'Tim Evaluator Inspektorat Daerah';

        return view('dashboard.isipenilaianlke.index', compact('components', 'documents', 'progress', 'year', 'deadline', 'isPastDeadline', 'isSubmitted', 'isApproved', 'namaPemeriksa'));
    }
    public function store(Request $request)
    {
        $institutionId = Auth::user()->institution_id;
        $year = date('Y');

        // Proteksi 1: Cek Batas Waktu Pengerjaan
        $deadlineSetting = DB::table('app_settings')->where('key', 'lke_deadline')->first();
        if ($deadlineSetting && now() > \Carbon\Carbon::parse($deadlineSetting->value)) {
            return back()->with('error', 'Gagal menyimpan! Waktu pengerjaan pengisian LKE telah berakhir.');
        }

        // Proteksi 2: Cek apakah dinas sudah memencet tombol "Ajukan"
        $isSubmitted = LkeEvaluation::where('institution_id', $institutionId)
            ->where('evaluation_year', $year)
            ->where('status', 'submitted')
            ->exists();

        if ($isSubmitted) {
            return back()->with('error', 'Gagal menyimpan! Berkas LKE Anda saat ini terkunci karena sedang dalam proses pemeriksaan Inspektorat.');
        }

        $request->validate([
            'lke_criteria_id' => 'required|exists:lke_criteria,id',
            'predikat' => 'required|string',
            'document_ids' => 'nullable|array' 
        ]);

        // Proteksi 3: Jika kriteria individual sudah disetujui Inspektorat
        $existingEval = LkeEvaluation::where('institution_id', $institutionId)
            ->where('lke_criteria_id', $request->lke_criteria_id)
            ->where('evaluation_year', $year)
            ->first();

        if ($existingEval && $existingEval->status == 'disetujui') {
            return back()->with('error', 'Akses dikunci! Kriteria ini telah selesai divalidasi dan disetujui oleh Inspektorat.');
        }

        $criteria = LkeCriteria::with('subComponent.component')->find($request->lke_criteria_id);
        $weight = $criteria->subComponent->weight ?? $criteria->subComponent->component->weight;
        
        $multiplier = 0;
        switch (strtoupper($request->predikat)) {
            case 'AA': $multiplier = 1.0; break;
            case 'A':  $multiplier = 0.9; break;
            case 'BB': $multiplier = 0.8; break;
            case 'B':  $multiplier = 0.7; break;
            case 'CC': $multiplier = 0.6; break;
            case 'C':  $multiplier = 0.5; break;
            case 'D':  $multiplier = 0.3; break;
            case 'E':  $multiplier = 0.0; break;
        }
        $finalScore = $multiplier * $weight;

        $eval = LkeEvaluation::updateOrCreate(
            [
                'institution_id' => $institutionId,
                'lke_criteria_id' => $request->lke_criteria_id,
                'evaluation_year' => $year,
            ],
            [
                'predicate' => $request->predikat,
                'final_score' => $finalScore,
                'status' => 'menunggu', 
            ]
        );

        if ($request->has('document_ids')) {
            $eval->documents()->sync($request->document_ids);
        } else {
            $eval->documents()->sync([]); 
        }

        return back()->with('success', 'Penilaian mandiri dan link evidence berhasil disimpan!');
    }

    public function submitToInspektorat(Request $request)
    {
        $institutionId = Auth::user()->institution_id;
        $year = date('Y');

        // Ambil evaluasi tahun berjalan yang statusnya bukan disetujui
        $evaluations = LkeEvaluation::where('institution_id', $institutionId)
            ->where('evaluation_year', $year)
            ->where('status', '!=', 'disetujui')
            ->get();

        if ($evaluations->isEmpty()) {
            return back()->with('error', 'Gagal mengajukan! Silakan lengkapi data kriteria lembar kerja LKE Anda terlebih dahulu.');
        }

        // UPDATE STATUS MENJADI 'submitted' (MENGUNCI AKSES DINAS)
        LkeEvaluation::where('institution_id', $institutionId)
            ->where('evaluation_year', $year)
            ->where('status', '!=', 'disetujui')
            ->update(['status' => 'submitted']);

        return back()->with('success', 'Lembar Kerja Evaluasi (LKE) Anda berhasil diajukan ke Inspektorat! Hak akses pengisian mandiri Anda dibekukan sementara.');
    }
}