@extends('layouts.dashboard')

@section('content')

    <div class="flex-1 flex flex-col h-full relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Dashboard Overview</h1>
            <div class="flex items-center gap-4">
                <button class="text-gray-400 hover:text-accent transition relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-maroon text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full">1</span>
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50">
            
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang di SI-AKSEL, {{ auth()->user()->name }}!</h2>
                <p class="text-gray-500 mt-1">Sistem Informasi Asistensi Kinerja dan Sistem Evaluasi LKE Kota Makassar</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">
                                {{ auth()->user()->hasAnyRole(['super_admin', 'admin', 'inspektorat', 'operator_inspektorat']) ? 'Rata-rata Progres Kota' : 'Progres Isi LKE Anda' }}
                            </p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $overallProgress }}%</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-teal-50 flex items-center justify-center text-accent text-xl">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-accent h-2 rounded-full" style="width: {{ $overallProgress }}%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-maroon">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Menunggu Pemeriksaan</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2">
                                {{ $awaitingReview }} {{ auth()->user()->hasAnyRole(['super_admin', 'admin', 'inspektorat', 'operator_inspektorat']) ? 'Instansi' : 'Kriteria LKE' }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center text-maroon text-xl">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-4"><i class="fas fa-info-circle text-maroon mr-1"></i> Data evaluasi tahun berjalan.</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Dokumen Evidence</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalDocuments) }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-800 text-xl">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-4">Tersimpan aman di Bank Dokumen.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">
                        {{ auth()->user()->hasAnyRole(['super_admin', 'admin', 'inspektorat', 'operator_inspektorat']) ? 'Status Penilaian Seluruh Instansi' : 'Status Penilaian Instansi Anda' }}
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                                <th class="px-6 py-3 font-medium">Nama Instansi</th>
                                <th class="px-6 py-3 font-medium">Progres Pengisian</th>
                                <th class="px-6 py-3 font-medium">Nilai Sementara</th>
                                <th class="px-6 py-3 font-medium">Status Evaluasi</th>
                                <th class="px-6 py-3 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            @forelse($tableData as $row)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $row['name'] }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-accent h-1.5 rounded-full" style="width: {{ $row['progress'] }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold">{{ $row['progress'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-accent">{{ number_format($row['score'], 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full {{ $row['class'] }} text-xs font-semibold border shadow-sm">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($row['is_dinas'])
                                            <a href="{{ route('dashboard.isipenilaianlke.index') }}" class="text-accent hover:text-maroon font-semibold text-xs border border-accent hover:bg-teal-50 px-3 py-1.5 rounded-lg transition">
                                                <i class="fas fa-edit mr-1"></i> Isi LKE
                                            </a>
                                        @else
                                            <a href="{{ route('dashboard.evaluasiinstansi.index') }}" class="text-maroon hover:bg-red-50 border border-maroon font-semibold text-xs px-3 py-1.5 rounded-lg transition">
                                                <i class="fas fa-search-plus mr-1"></i> Periksa
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data evaluasi instansi tersedia.</td>
                                endtr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
@endsection