<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    // Menampilkan halaman Master Instansi
    public function index()
    {
        // Ambil semua instansi beserta jumlah usernya (users_count)
        $institutions = Institution::withCount('users')->get();
        
        // Hitung total instansi yang aktif untuk ditampilkan di Card atas
        $totalAktif = Institution::where('status', 'aktif')->count();

        return view('dashboard.masterinstansi.index', compact('institutions', 'totalAktif'));
    }

    // Fungsi untuk menambah instansi baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
            'notes' => 'nullable|string',
        ]);

        // Simpan ke database
        Institution::create([
            'name' => $request->name,
            'alias' => strtoupper($request->alias), // Pastikan alias selalu huruf besar
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Data Instansi berhasil ditambahkan!');
    }
}