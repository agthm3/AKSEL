<?php

namespace App\Http\Controllers;

use App\Models\LkeComponent;
use App\Models\LkeCriteria;
use App\Models\LkeEvaluation;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsiPenilaianController extends Controller
{
    public function index()
    {
        $institutionId = Auth::user()->institution_id;
        $year = date('Y'); 

        // Gunakan 'documents' (jamak)
        $components = LkeComponent::with(['subComponents.criteria.evaluations' => function($query) use ($institutionId, $year) {
            $query->where('institution_id', $institutionId)
                  ->where('evaluation_year', $year)
                  ->with('documents'); // JAMAK
        }])->get();

        $documents = Document::where('institution_id', $institutionId)->orderBy('created_at', 'desc')->get();

        $totalCriteria = LkeCriteria::count();
        $filledCriteria = LkeEvaluation::where('institution_id', $institutionId)->where('evaluation_year', $year)->whereNotNull('predicate')->count();
        $progress = $totalCriteria > 0 ? round(($filledCriteria / $totalCriteria) * 100) : 0;

        return view('dashboard.isipenilaianlke.index', compact('components', 'documents', 'progress', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lke_criteria_id' => 'required|exists:lke_criteria,id',
            'predikat' => 'required|string',
            'document_ids' => 'nullable|array' // Mengizinkan lebih dari 1 dokumen (Array)
        ]);

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

        // Simpan Data Penilaian
        $eval = LkeEvaluation::updateOrCreate(
            [
                'institution_id' => Auth::user()->institution_id,
                'lke_criteria_id' => $request->lke_criteria_id,
                'evaluation_year' => date('Y'),
            ],
            [
                'predicate' => $request->predikat,
                'final_score' => $finalScore,
                'status' => 'menunggu',
            ]
        );

        // TAUTKAN BANYAK DOKUMEN MENGGUNAKAN SYNC
        if ($request->has('document_ids')) {
            $eval->documents()->sync($request->document_ids);
        } else {
            $eval->documents()->sync([]); // Hapus tautan jika tidak ada yg dicentang
        }

        return back()->with('success', 'Penilaian mandiri dan link evidence berhasil disimpan!');
    }
}