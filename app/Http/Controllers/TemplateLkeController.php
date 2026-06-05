<?php

namespace App\Http\Controllers;

use App\Models\LkeComponent;
use App\Models\LkeSubComponent;
use App\Models\LkeCriteria;
use Illuminate\Http\Request;

class TemplateLkeController extends Controller
{
    public function index()
    {
        $components = LkeComponent::with(['subComponents.criteria'])->get();
        return view('dashboard.kelolatemplatelke.index', compact('components'));
    }

    public function storeComponent(Request $request)
    {
        $request->validate([
            'component_number' => 'required|integer',
            'name' => 'required|string',
            'weight' => 'required|numeric'
        ]);
        LkeComponent::create($request->all());
        return back()->with('success', 'Komponen Utama berhasil ditambahkan!');
    }

    public function storeSubComponent(Request $request)
    {
        $request->validate([
            'lke_component_id' => 'required|exists:lke_components,id',
            'code' => 'required|string',
            'name' => 'required|string',
            'weight' => 'nullable|numeric'
        ]);
        LkeSubComponent::create($request->all());
        return back()->with('success', 'Sub-Komponen berhasil ditambahkan!');
    }

    public function storeCriteria(Request $request)
    {
        $request->validate([
            'lke_sub_component_id' => 'required|exists:lke_sub_components,id',
            'criteria_number' => 'required|integer',
            'description' => 'required|string',
            'expected_evidence' => 'required|string'
        ]);
        LkeCriteria::create($request->all());
        return back()->with('success', 'Kriteria Evidence berhasil ditambahkan!');
    }
}