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
    public function index()
    {
        $user = Auth::user();
        $year = date('Y');

        // 1. Ambil daftar instansi sesuai hak akses
        // Jika Super Admin/Inspektorat Pimpinan: Lihat semua. Jika Operator: Hanya binaannya.
        if ($user->hasRole('super_admin') || $user->hasRole('inspektorat')) {
            $institutions = Institution::where('status', 'aktif')->get();
        } else {
            $institutions = $user->binaanInstitutions()->where('status', 'aktif')->get();
        }

        // 2. Ambil struktur template LKE
        $components = LkeComponent::with('subComponents.criteria')->get();

        // 3. Ambil seluruh data evaluasi pada tahun berjalan untuk instansi-instansi tersebut
        $allEvaluations = LkeEvaluation::with('documents')
            ->whereIn('institution_id', $institutions->pluck('id'))
            ->where('evaluation_year', $year)
            ->get()
            ->groupBy('institution_id');

        return view('dashboard.evaluasiinstansi.index', compact('institutions', 'components', 'allEvaluations', 'year'));
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
                // Hitung ulang skor jika Inspektorat mengubah predikat
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
                    'status' => $request->status_akhir // 'disetujui' atau 'revisi'
                ]);
            }
        }

        $pesan = $request->status_akhir == 'disetujui' 
            ? 'Evaluasi disetujui! Nilai akhir telah dikunci.' 
            : 'Dokumen dikembalikan ke instansi untuk direvisi.';

        return back()->with('success', $pesan);
    }
}