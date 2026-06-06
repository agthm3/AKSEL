<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\LkeComponent;
use App\Models\LkeEvaluation;
use Illuminate\Http\Request;

class RekapitulasiController extends Controller
{
    public function index()
    {
        $year = date('Y');

        // 1. Ambil semua instansi yang aktif
        $institutions = Institution::where('status', 'aktif')->get();
        $totalInstitutions = $institutions->count();

        // 2. Ambil komponen utama untuk header tabel dinamis
        $components = LkeComponent::orderBy('component_number')->get();

        // 3. Ambil seluruh data evaluasi yang SUDAH DISETUJUI oleh Inspektorat
        // Jika Anda ingin menampilkan yang sedang proses juga, hapus: ->where('status', 'disetujui')
        $allEvaluations = LkeEvaluation::with('criteria.subComponent.component')
            ->where('evaluation_year', $year)
            ->where('status', 'disetujui')
            ->get()
            ->groupBy('institution_id');

        $rekapData = [];
        $totalKotaScore = 0;
        $instansiSelesai = 0;
        $predikatCounts = ['AA' => 0, 'A' => 0, 'BB' => 0, 'B' => 0, 'CC' => 0, 'C' => 0, 'D' => 0];

        // 4. Hitung Nilai Per Instansi
        foreach ($institutions as $inst) {
            $evals = $allEvaluations->get($inst->id);
            
            if ($evals) {
                $instansiSelesai++;
                $totalScore = $evals->sum('final_score');
                $totalKotaScore += $totalScore;

                // Tentukan Predikat
                $predikat = 'D';
                if ($totalScore > 90) $predikat = 'AA';
                elseif ($totalScore > 80) $predikat = 'A';
                elseif ($totalScore > 70) $predikat = 'BB';
                elseif ($totalScore > 60) $predikat = 'B';
                elseif ($totalScore > 50) $predikat = 'CC';
                elseif ($totalScore > 30) $predikat = 'C';
                
                $predikatCounts[$predikat]++;

                // Kelompokkan nilai per Komponen (Perencanaan, Pengukuran, dll)
                $componentScores = [];
                foreach ($components as $comp) {
                    $componentScores[$comp->id] = 0; // Default 0
                }

                foreach ($evals as $eval) {
                    if ($eval->criteria && $eval->criteria->subComponent) {
                        $compId = $eval->criteria->subComponent->lke_component_id;
                        if (isset($componentScores[$compId])) {
                            $componentScores[$compId] += $eval->final_score;
                        }
                    }
                }

                $rekapData[] = [
                    'institution_name' => $inst->name,
                    'total_score' => $totalScore,
                    'predikat' => $predikat,
                    'component_scores' => $componentScores,
                    'status' => 'Selesai'
                ];
            } else {
                // Instansi belum di-review / belum submit
                $rekapData[] = [
                    'institution_name' => $inst->name,
                    'total_score' => 0,
                    'predikat' => 'N/A',
                    'component_scores' => array_fill_keys($components->pluck('id')->toArray(), 0),
                    'status' => 'Proses'
                ];
            }
        }

        // 5. Urutkan berdasarkan nilai tertinggi (Peringkat 1 di atas)
        usort($rekapData, function($a, $b) {
            return $b['total_score'] <=> $a['total_score']; // Descending
        });

        // 6. Hitung Statistik untuk Cards
        $rataRataKota = $instansiSelesai > 0 ? ($totalKotaScore / $instansiSelesai) : 0;
        
        $rataRataPredikat = 'D';
        if ($rataRataKota > 90) $rataRataPredikat = 'AA';
        elseif ($rataRataKota > 80) $rataRataPredikat = 'A';
        elseif ($rataRataKota > 70) $rataRataPredikat = 'BB';
        elseif ($rataRataKota > 60) $rataRataPredikat = 'B';
        elseif ($rataRataKota > 50) $rataRataPredikat = 'CC';
        elseif ($rataRataKota > 30) $rataRataPredikat = 'C';

        $progressPersen = $totalInstitutions > 0 ? round(($instansiSelesai / $totalInstitutions) * 100) : 0;

        return view('dashboard.rekapitulasinilaiakhir.index', compact(
            'year', 'components', 'rekapData', 'rataRataKota', 'rataRataPredikat', 
            'instansiSelesai', 'totalInstitutions', 'progressPersen', 'predikatCounts'
        ));
    }
}