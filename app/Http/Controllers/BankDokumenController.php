<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BankDokumenController extends Controller
{
    public function index()
    {
        // Ambil ID instansi dari user yang sedang login (Operator Dinas)
        $institutionId = Auth::user()->institution_id;

        // Ambil dokumen milik instansi tersebut, hitung juga berapa kali ditautkan (evaluations_count)
        $documents = Document::where('institution_id', $institutionId)
            ->withCount('evaluations')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.bankdokumen.index', compact('documents'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'file' => 'required|file|mimes:pdf|max:10240', // Wajib PDF, max 10MB
        ]);

        // 2. Simpan file PDF ke folder 'public/documents'
        $filePath = $request->file('file')->store('documents', 'public');

        // 3. Simpan data ke database
        Document::create([
            'institution_id' => Auth::user()->institution_id,
            'name' => $request->name,
            'year' => $request->year,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Dokumen evidence berhasil diunggah dan disimpan di Bank Dokumen!');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Pastikan hanya dokumen milik instansinya sendiri yang bisa dihapus
        if ($document->institution_id !== Auth::user()->institution_id) {
            abort(403, 'Unauthorized action.');
        }

        // Jangan izinkan hapus jika dokumen sudah ditautkan ke LKE
        if ($document->evaluations()->count() > 0) {
            return back()->with('error', 'Dokumen tidak dapat dihapus karena sedang ditautkan pada penilaian LKE!');
        }

        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Hapus data dari database
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}