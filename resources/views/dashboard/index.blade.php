@extends('layouts.dashboard')

@section('content')

    <div class="flex-1 flex flex-col h-full relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Dashboard Overview</h1>
            <div class="flex items-center gap-4">
                <button class="text-gray-400 hover:text-accent transition relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-maroon text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full">3</span>
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50">
            
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang di SI-AKSEL</h2>
                <p class="text-gray-500 mt-1">Sistem Informasi Asistensi Kinerja dan Sistem Evaluasi LKE Kota Makassar</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Progres LKE Keseluruhan</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2">68%</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-teal-50 flex items-center justify-center text-accent text-xl">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-accent h-2 rounded-full" style="width: 68%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-maroon">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Menunggu Pemeriksaan</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2">12 Instansi</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center text-maroon text-xl">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-4"><i class="fas fa-arrow-up text-maroon"></i> 3 instansi baru minggu ini</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-800">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Dokumen Evidence</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2">1,240</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-800 text-xl">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-4">Diunggah di Bank Dokumen</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Status Evaluasi Instansi (Dummy)</h3>
                    <button class="bg-maroon hover:bg-red-900 text-white px-4 py-2 rounded-lg text-sm transition shadow-md">
                        Lihat Semua
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                                <th class="px-6 py-3 font-medium">Nama Instansi</th>
                                <th class="px-6 py-3 font-medium">Progres Evidence</th>
                                <th class="px-6 py-3 font-medium">Nilai Sementara</th>
                                <th class="px-6 py-3 font-medium">Status Evaluasi</th>
                                <th class="px-6 py-3 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">Dinas Kominfo</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 w-24">
                                            <div class="bg-accent h-1.5 rounded-full" style="width: 100%"></div>
                                        </div>
                                        <span class="text-xs">100%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-accent">85.5 (A)</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Selesai Diperiksa</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-accent hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">BRIDA Kota Makassar</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 w-24">
                                            <div class="bg-yellow-400 h-1.5 rounded-full" style="width: 60%"></div>
                                        </div>
                                        <span class="text-xs">60%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-500">-</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Sedang Mengisi</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-accent hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">Dinas Pekerjaan Umum</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 w-24">
                                            <div class="bg-maroon h-1.5 rounded-full" style="width: 100%"></div>
                                        </div>
                                        <span class="text-xs">100%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-500">Menunggu</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-maroon text-xs font-semibold">Menunggu Pemeriksaan</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-maroon font-semibold hover:underline">Periksa</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
@endsection