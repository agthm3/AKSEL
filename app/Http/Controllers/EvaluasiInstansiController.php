<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\LkeComponent;
use App\Models\LkeCriteria;
use App\Models\LkeEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiInstansiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $year = date('Y');
        $filterStatus = $request->get('status');

        // 1. Ambil daftar instansi sesuai hak akses
        if ($user->hasRole('super_admin') || $user->hasRole('inspektorat')) {
            $institutionsQuery = Institution::where('status', 'aktif');
        } else {
            $institutionsQuery = $user->binaanInstitutions()->where('status', 'aktif');
        }

        $institutions = $institutionsQuery->get();

        // 2. Ambil struktur template LKE
        $components = LkeComponent::with('subComponents.criteria')->get();

        // 3. Ambil seluruh data evaluasi pada tahun berjalan
        $allEvaluations = LkeEvaluation::with('documents')
            ->whereIn('institution_id', $institutions->pluck('id'))
            ->where('evaluation_year', $year)
            ->get()
            ->groupBy('institution_id');

        // 4. Petakan status riil dinas dan lakukan filtering di memory Laravel
        $mappedInstitutions = [];
        $awaitingCount = 0;

        foreach ($institutions as $instansi) {
            $evals = $allEvaluations->get($instansi->id);
            $totalFilled = $evals ? $evals->whereNotNull('predicate')->count() : 0;
            $totalScore = $evals ? $evals->sum('final_score') : 0;

            // Logika Penentuan Status Riil Instansi Binaan
            if (!$evals || $evals->count() == 0) {
                $statusText = 'Belum Mengisi';
                $statusClass = 'bg-gray-100 text-gray-600 border-gray-200';
            } elseif ($evals->contains('status', 'submitted')) { // <--- TAMBAHKAN KONDISI INI
                $statusText = 'Siap Diperiksa';
                $statusClass = 'bg-yellow-50 text-yellow-700 border-yellow-200 animate-pulse';
                $awaitingCount++;
            } elseif ($evals->contains('status', 'revisi')) {
                $statusText = 'Butuh Revisi';
                $statusClass = 'bg-red-50 text-red-700 border-red-200';
            } elseif ($evals->contains('status', 'disetujui')) {
                $unapproved = $evals->where('status', '!=', 'disetujui')->count();
                if ($unapproved == 0) {
                    $statusText = 'Selesai Evaluasi';
                    $statusClass = 'bg-green-100 text-green-700 border-green-200';
                } else {
                    $statusText = 'Siap Diperiksa';
                    $statusClass = 'bg-yellow-50 text-yellow-700 border-yellow-200 animate-pulse';
                    $awaitingCount++;
                }
            } else {
                $statusText = 'Siap Diperiksa';
                $statusClass = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                $awaitingCount++;
            }

            $instansiData = [
                'model' => $instansi,
                'total_filled' => $totalFilled,
                'total_score' => $totalScore,
                'status_text' => $statusText,
                'status_class' => $statusClass,
                'evals' => $evals
            ];

            // Filter Kondisi
            if ($filterStatus) {
                if ($filterStatus == 'belum_mengisi' && $statusText == 'Belum Mengisi') $mappedInstitutions[] = $instansiData;
                if ($filterStatus == 'siap_diperiksa' && $statusText == 'Siap Diperiksa') $mappedInstitutions[] = $instansiData;
                if ($filterStatus == 'revisi' && $statusText == 'Butuh Revisi') $mappedInstitutions[] = $instansiData;
                if ($filterStatus == 'selesai' && $statusText == 'Selesai Evaluasi') $mappedInstitutions[] = $instansiData;
            } else {
                $mappedInstitutions[] = $instansiData;
            }
        }

        return view('dashboard.evaluasiinstansi.index', compact('mappedInstitutions', 'components', 'allEvaluations', 'year', 'awaitingCount', 'filterStatus'));
    }

    public function store(Request $request, $institutionId)
    {
        $request->validate([
            'evaluations' => 'required|array',
            'status_akhir' => 'required|in:disetujui,revisi'
        ]);

        foreach ($request->evaluations as $evalId => $data) {
            $eval = LkeEvaluation::where('id', $evalId)->where('institution_id', $institutionId)->first();
            
            if ($eval && isset($data['predicate'])) {
                $criteria = LkeCriteria::with('subComponent.component')->find($eval->lke_criteria_id);
                $weight = $criteria->subComponent->weight ?? $criteria->subComponent->component->weight;
                
                $multiplier = 0;
                switch (strtoupper($data['predicate'])) {
                    case 'AA': $multiplier = 1.0; break;
                    case 'A':  $multiplier = 0.9; break;
                    case 'BB': $multiplier = 0.8; break;
                    case 'B':  $multiplier = 0.7; break;
                    case 'CC': $multiplier = 0.6; break;
                    case 'C':  $multiplier = 0.5; break;
                    case 'D':  $multiplier = 0.3; break;
                    case 'E':  $multiplier = 0.0; break;
                }

                $eval->update([
                    'predicate' => $data['predicate'],
                    'final_score' => $multiplier * $weight,
                    'inspector_notes' => $data['notes'] ?? null,
                    'status' => $request->status_akhir
                ]);
            }
        }

        $pesan = $request->status_akhir == 'disetujui' 
            ? 'Evaluasi disetujui! Nilai akhir telah divalidasi.' 
            : 'Dokumen dikembalikan ke instansi untuk direvisi.';

        return back()->with('success', $pesan);
    }
}