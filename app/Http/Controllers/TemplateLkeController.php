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

    public function destroyCriteria($id)
    {
        // Pastikan hanya role yang berwenang yang bisa mengeksekusi (Double Security)
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin', 'inspektorat'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus kriteria ini.');
        }

        $criteria = LkeCriteria::findOrFail($id);
        
        // Hapus kriteria (Relasi cascade di database akan otomatis menghapus tautan pivot jika ada)
        $criteria->delete();

        return back()->with('success', 'Kriteria evidence berhasil dihapus dari struktur LKE!');
    }

    public function destroyComponent($id)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin', 'inspektorat'])) {
            abort(403);
        }

        $component = LkeComponent::findOrFail($id);
        $component->delete(); // Otomatis menghapus Sub & Kriteria di bawahnya karena Cascade

        return back()->with('success', 'Komponen utama beserta seluruh sub-komponen dan kriteria di bawahnya berhasil dihapus!');
    }

    public function destroySubComponent($id)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin', 'inspektorat'])) {
            abort(403);
        }

        $subComponent = LkeSubComponent::findOrFail($id);
        $subComponent->delete(); // Otomatis menghapus Kriteria di bawahnya karena Cascade

        return back()->with('success', 'Sub-komponen beserta seluruh kriteria di bawahnya berhasil dihapus!');
    }

    public function updateDeadline(Request $request)
    {
        $request->validate([
            'deadline_date' => 'required|date|after:now',
        ]);

        \DB::table('app_settings')
            ->where('key', 'lke_deadline')
            ->update([
                'value' => $request->deadline_date,
                'updated_at' => now()
            ]);

        return back()->with('success', 'Batas waktu pengerjaan LKE berhasil diperbarui!');
    }
}