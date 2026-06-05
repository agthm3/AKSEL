<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Institution;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan halaman Manajemen Pengguna
    public function index()
    {
        // Ambil semua user beserta instansi asal, instansi binaan, dan rolenya
        $users = User::with(['originInstitution', 'binaanInstitutions', 'roles'])->get();
        
        // Ambil semua instansi yang statusnya aktif untuk ditampilkan di modal Assign
        $institutions = Institution::where('status', 'aktif')->get();

        // Nanti kirim ke view blade Anda
        return view('dashboard.manajemenpengguna.index', compact('users', 'institutions'));
    }

    // Fungsi canggih untuk Menyimpan Assign Dinas (Lewat Pop-up Modal)
    public function assignDinas(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Pastikan yang di-assign hanya yang punya role operator_inspektorat
        if (!$user->hasRole('operator_inspektorat')) {
            return back()->with('error', 'Hanya Operator Inspektorat yang bisa diberi instansi binaan.');
        }

        // Fitur canggih Laravel 'sync': 
        // Otomatis menghapus assign lama dan memasukkan assign baru sesuai checkbox di Modal
        $user->binaanInstitutions()->sync($request->input('institution_ids', []));

        return back()->with('success', 'Penugasan instansi binaan berhasil diperbarui!');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string',
            'institution_id' => 'required|exists:institutions,id',
            // Default password untuk user baru adalah 'password123' (Bisa diubah nanti)
        ]);

        // Simpan data ke tabel users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password123'),
            'institution_id' => $request->institution_id,
        ]);

        // Berikan role menggunakan Spatie
        $user->assignRole($request->role);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan! Password default: password123');
    }
}