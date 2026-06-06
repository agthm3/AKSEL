<?php

namespace App\Http\Controllers;

use App\Models\LkeEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatEvaluasiController extends Controller
{
    public function index()
    {
        $institutionId = Auth::user()->institution_id;

        // Ambil semua evaluasi instansi ini, urutkan dari tahun terbaru
        $evaluations = LkeEvaluation::with('criteria.subComponent.component')
            ->where('institution_id', $institutionId)
            ->orderBy('evaluation_year', 'desc')
            ->get()
            ->groupBy('evaluation_year');

        $riwayat = [];

        foreach ($evaluations as $year => $evals) {
            $totalScore = $evals->sum('final_score');
            
            // Konversi Total Skor menjadi Predikat AKIP
            $predikat = '-';
            if ($totalScore > 90) $predikat = 'AA';
            elseif ($totalScore > 80) $predikat = 'A';
            elseif ($totalScore > 70) $predikat = 'BB';
            elseif ($totalScore > 60) $predikat = 'B';
            elseif ($totalScore > 50) $predikat = 'CC';
            elseif ($totalScore > 30) $predikat = 'C';
            elseif ($totalScore > 0) $predikat = 'D';

            // Tentukan Status Global untuk tahun tersebut
            if ($evals->contains('status', 'revisi')) {
                $status = 'Revisi';
            } elseif ($evals->contains('status', 'menunggu')) {
                $status = 'Menunggu Diperiksa';
            } else {
                $status = 'Selesai Evaluasi';
            }

            // Kelompokkan skor berdasarkan Komponen untuk ditampilkan di Modal Detail
            $componentScores = [];
            foreach ($evals as $eval) {
                // Pastikan relasi tidak null
                if($eval->criteria && $eval->criteria->subComponent && $eval->criteria->subComponent->component) {
                    $comp = $eval->criteria->subComponent->component;
                    
                    if (!isset($componentScores[$comp->id])) {
                        $componentScores[$comp->id] = [
                            'number' => $comp->component_number,
                            'name' => $comp->name,
                            'max_weight' => $comp->weight,
                            'score' => 0
                        ];
                    }
                    $componentScores[$comp->id]['score'] += $eval->final_score;
                }
            }

            // Ambil semua catatan dari inspektorat yang tidak kosong
            $notes = $evals->whereNotNull('inspector_notes')->pluck('inspector_notes')->filter()->toArray();

            $riwayat[] = [
                'year' => $year,
                'total_score' => $totalScore,
                'predikat' => $predikat,
                'status' => $status,
                'date' => $evals->first()->updated_at->format('d M Y'),
                'components' => collect($componentScores)->sortBy('number')->values()->all(),
                'notes' => $notes
            ];
        }

        return view('dashboard.riwayatevaluasi.index', compact('riwayat'));
    }
}