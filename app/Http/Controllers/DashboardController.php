<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Institution;
use App\Models\LkeCriteria;
use App\Models\LkeEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $year = date('Y');
        $totalCriteria = LkeCriteria::count();

        // JIKA YANG LOGIN ADALAH TIM MANAJEMEN / INSPEKTORAT
        if ($user->hasAnyRole(['super_admin', 'admin', 'inspektorat', 'operator_inspektorat'])) {
            $institutions = Institution::where('status', 'aktif')->get();
            $totalInstitutions = $institutions->count();

            // 1. Hitung total dokumen se-Kota Makassar
            $totalDocuments = Document::count();

            // 2. Hitung berapa instansi yang status LKE-nya memiliki kriteria bernilai 'menunggu' pemeriksaan
            $awaitingReview = LkeEvaluation::where('evaluation_year', $year)
                ->where('status', 'menunggu')
                ->distinct('institution_id')
                ->count('institution_id');

            // 3. Hitung Progres Rata-rata LKE se-Kota Makassar
            $allEvaluations = LkeEvaluation::where('evaluation_year', $year)->get()->groupBy('institution_id');
            
            $totalProgressPercent = 0;
            $tableData = [];

            foreach ($institutions as $inst) {
                $evals = $allEvaluations->get($inst->id);
                $filledCount = $evals ? $evals->whereNotNull('predicate')->count() : 0;
                $progress = $totalCriteria > 0 ? round(($filledCount / $totalCriteria) * 100) : 0;
                $totalProgressPercent += $progress;

                $score = $evals ? $evals->sum('final_score') : 0;
                
                // Tentukan status global instansi
                if ($evals && $evals->contains('status', 'revisi')) {
                    $status = 'Butuh Revisi';
                    $statusClass = 'bg-red-100 text-red-700';
                } elseif ($evals && $evals->contains('status', 'menunggu')) {
                    $status = 'Menunggu Diperiksa';
                    $statusClass = 'bg-yellow-100 text-yellow-700 animate-pulse';
                } elseif ($evals && $evals->count() > 0) {
                    $status = 'Selesai Evaluasi';
                    $statusClass = 'bg-green-100 text-green-700';
                } else {
                    $status = 'Belum Mengisi';
                    $statusClass = 'bg-gray-100 text-gray-500';
                }

                $tableData[] = [
                    'name' => $inst->name,
                    'progress' => $progress,
                    'score' => $score,
                    'status' => $status,
                    'class' => $statusClass,
                    'is_dinas' => false
                ];
            }

            $overallProgress = $totalInstitutions > 0 ? round($totalProgressPercent / $totalInstitutions) : 0;

        } else {
            // JIKA YANG LOGIN ADALAH OPERATOR DINAS / SKPD BINAAN
            $instId = $user->institution_id;
            $institution = Institution::find($instId);

            // 1. Hitung dokumen milik instansi sendiri
            $totalDocuments = Document::where('institution_id', $instId)->count();

            // 2. Ambil data evaluasi mandiri instansi
            $evals = LkeEvaluation::where('institution_id', $instId)
                ->where('evaluation_year', $year)
                ->get();

            $filledCount = $evals->whereNotNull('predicate')->count();
            
            // 3. Progres pengisian mandiri
            $overallProgress = $totalCriteria > 0 ? round(($filledCount / $totalCriteria) * 100) : 0;

            // Bagi dinas, "menunggu" mengindikasikan berapa kriterianya yang sedang antre diperiksa Inspektorat
            $awaitingReview = $evals->where('status', 'menunggu')->count();

            $score = $evals->sum('final_score');
            if ($evals->contains('status', 'revisi')) {
                $status = 'Butuh Revisi';
                $statusClass = 'bg-red-100 text-red-700';
            } elseif ($evals->contains('status', 'menunggu')) {
                $status = 'Menunggu Diperiksa';
                $statusClass = 'bg-yellow-100 text-yellow-700';
            } elseif ($evals->count() > 0) {
                $status = 'Selesai Evaluasi';
                $statusClass = 'bg-green-100 text-green-700';
            } else {
                $status = 'Belum Mengisi';
                $statusClass = 'bg-gray-100 text-gray-500';
            }

            $tableData[] = [
                'name' => $institution->name ?? 'Instansi Anda',
                'progress' => $overallProgress,
                'score' => $score,
                'status' => $status,
                'class' => $statusClass,
                'is_dinas' => true
            ];
        }

        return view('dashboard.index', compact('overallProgress', 'awaitingReview', 'totalDocuments', 'tableData'));
    }
}