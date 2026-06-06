<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['originInstitution', 'binaanInstitutions', 'roles'])->get();
        $institutions = Institution::where('status', 'aktif')->get();

        return view('dashboard.manajemenpengguna.index', compact('users', 'institutions'));
    }

    public function assignDinas(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!$user->hasRole('operator_inspektorat')) {
            return back()->with('error', 'Hanya Operator Inspektorat yang bisa diberi instansi binaan.');
        }

        $user->binaanInstitutions()->sync($request->input('institution_ids', []));

        return back()->with('success', 'Penugasan instansi binaan berhasil diperbarui!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string',
            'institution_id' => 'required|exists:institutions,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password123'), // Password default
            'institution_id' => $request->institution_id,
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan! Password default: password123');
    }

    // FUNGSI BARU: EDIT PENGGUNA
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'institution_id' => 'required|exists:institutions,id',
        ];

        // Jika yang login adalah super_admin, validasi input role-nya juga
        if (Auth::user()->hasRole('super_admin')) {
            $rules['role'] = 'required|string';
        }

        $request->validate($rules);

        // Update data dasar
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'institution_id' => $request->institution_id,
        ]);

        // Sync role HANYA jika yang mengubah adalah super_admin
        if (Auth::user()->hasRole('super_admin')) {
            $user->syncRoles([$request->role]);
        }

        return back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    // FUNGSI BARU: HAPUS PENGGUNA
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteksi: Tidak boleh menghapus diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return back()->with('success', 'Akun pengguna berhasil dihapus dari sistem!');
    }
}