<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\TemplateLkeController;
use App\Http\Controllers\BankDokumenController;
use App\Http\Controllers\IsiPenilaianController;
use App\Http\Controllers\EvaluasiInstansiController;
use App\Http\Controllers\RiwayatEvaluasiController;
use App\Http\Controllers\RekapitulasiController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    
    // ==========================================
    // AKES UMUM: SEMUA ROLE BISA DIAKSES
    // ==========================================
    // Rute Profil Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard Home Overview
    Route::get('/dashboard/home', [DashboardController::class, 'index'])->name('dashboard.index');


    // ==========================================
    // AKSES ADMINISTRATOR: HANYA SUPER ADMIN & ADMIN
    // ==========================================
    Route::middleware(['role:super_admin|admin'])->group(function () {
        // Manajemen Pengguna
        Route::get('/dashboard/manajemenpengguna', [UserController::class, 'index'])->name('dashboard.manajemenpengguna.index');
        Route::post('/dashboard/manajemenpengguna/store', [UserController::class, 'store'])->name('dashboard.manajemenpengguna.store');
        Route::post('/dashboard/manajemenpengguna/{id}/assign', [UserController::class, 'assignDinas'])->name('dashboard.manajemenpengguna.assign');
        Route::put('/dashboard/manajemenpengguna/{id}', [UserController::class, 'update'])->name('dashboard.manajemenpengguna.update');
        Route::delete('/dashboard/manajemenpengguna/{id}', [UserController::class, 'destroy'])->name('dashboard.manajemenpengguna.destroy');

        // Master Instansi
        Route::get('/dashboard/masterinstansi', [InstitutionController::class, 'index'])->name('dashboard.masterinstansi.index');
        Route::post('/dashboard/masterinstansi/store', [InstitutionController::class, 'store'])->name('dashboard.masterinstansi.store');

        // Pengaturan Batas Waktu (Deadline LKE)
        Route::post('/dashboard/kelolatemplatelke/deadline', [TemplateLkeController::class, 'updateDeadline'])->name('dashboard.kelolatemplatelke.updateDeadline');
    });


    // ==========================================
    // AKSES INSPEKTORAT & TIM MANAGEMENT
    // ==========================================
    Route::middleware(['role:super_admin|admin|inspektorat|operator_inspektorat'])->group(function () {
        // Kelola & Susun Struktur Template LKE
        Route::get('/dashboard/kelolatemplatelke', [TemplateLkeController::class, 'index'])->name('dashboard.kelolatemplatelke.index');
        Route::post('/dashboard/kelolatemplatelke/component', [TemplateLkeController::class, 'storeComponent'])->name('dashboard.kelolatemplatelke.storeComponent');
        Route::post('/dashboard/kelolatemplatelke/subcomponent', [TemplateLkeController::class, 'storeSubComponent'])->name('dashboard.kelolatemplatelke.storeSubComponent');
        Route::post('/dashboard/kelolatemplatelke/criteria', [TemplateLkeController::class, 'storeCriteria'])->name('dashboard.kelolatemplatelke.storeCriteria');
        
        // Hapus Komponen Struktural LKE (Cascade)
        Route::delete('/dashboard/kelolatemplatelke/criteria/{id}', [TemplateLkeController::class, 'destroyCriteria'])->name('dashboard.kelolatemplatelke.destroyCriteria');
        Route::delete('/dashboard/kelolatemplatelke/component/{id}', [TemplateLkeController::class, 'destroyComponent'])->name('dashboard.kelolatemplatelke.destroyComponent');
        Route::delete('/dashboard/kelolatemplatelke/subcomponent/{id}', [TemplateLkeController::class, 'destroySubComponent'])->name('dashboard.kelolatemplatelke.destroySubComponent');

        // Modul Pemeriksaan & Validasi Evaluasi Instansi Binaan
        Route::get('/dashboard/evaluasiinstansi', [EvaluasiInstansiController::class, 'index'])->name('dashboard.evaluasiinstansi.index');
        Route::post('/dashboard/evaluasiinstansi/{institution}/store', [EvaluasiInstansiController::class, 'store'])->name('dashboard.evaluasiinstansi.store');

        // Modul Rekapitulasi & Pemantauan Nilai Akhir Kota
        Route::get('/dashboard/rekapitulasinilaiakhir', [RekapitulasiController::class, 'index'])->name('dashboard.rekapitulasinilaiakhir.index');
    });


    // ==========================================
    // AKSES OPERATOR DINAS / SKPD BINAAN
    // ==========================================
    Route::middleware(['role:super_admin|admin|operator_dinas'])->group(function () {
        // Bank Dokumen Evidence SKPD
        Route::get('/dashboard/bankdokumen', [BankDokumenController::class, 'index'])->name('dashboard.bankdokumen.index');
        Route::post('/dashboard/bankdokumen/store', [BankDokumenController::class, 'store'])->name('dashboard.bankdokumen.store');
        Route::delete('/dashboard/bankdokumen/{id}', [BankDokumenController::class, 'destroy'])->name('dashboard.bankdokumen.destroy');

        // Lembar Kerja Isi Penilaian Mandiri LKE
        Route::get('/dashboard/isipenilaianlke', [IsiPenilaianController::class, 'index'])->name('dashboard.isipenilaianlke.index');
        Route::post('/dashboard/isipenilaianlke/store', [IsiPenilaianController::class, 'store'])->name('dashboard.isipenilaianlke.store');
        
        // Tombol Ajukan Berkas Kolektif Ke Inspektorat
        Route::post('/dashboard/isipenilaianlke/submit', [IsiPenilaianController::class, 'submitToInspektorat'])->name('dashboard.isipenilaianlke.submit');

        // Rekam Jejak Riwayat Evaluasi Tahunan SKPD
        Route::get('/dashboard/riwayatevaluasi', [RiwayatEvaluasiController::class, 'index'])->name('dashboard.riwayatevaluasi.index');
    });

});

require __DIR__.'/auth.php';