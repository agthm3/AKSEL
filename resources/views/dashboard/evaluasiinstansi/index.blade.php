@extends('layouts.dashboard')

@section('content')
    <div class="flex-1 flex flex-col h-full relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Pemeriksaan & Evaluasi LKE</h1>
            <div class="bg-red-50 text-maroon px-4 py-1.5 rounded-full text-sm font-bold border border-red-100">
                <i class="fas fa-bell mr-1"></i> 3 Menunggu Pemeriksaan
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50 relative">
            
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-lg text-sm font-bold flex justify-between items-center">
                    <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Instansi (Tahun {{ $year }})</h2>
                <p class="text-gray-500 mt-1 text-sm">Pilih instansi yang telah mengajukan LKE untuk dilakukan proses verifikasi dan penilaian akhir.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Nama Instansi</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Total Kriteria Diisi</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Nilai Sementara</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Aksi Pemeriksaan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse($institutions as $instansi)
                            @php
                                $evals = $allEvaluations->get($instansi->id);
                                $totalFilled = $evals ? $evals->count() : 0;
                                $totalScore = $evals ? $evals->sum('final_score') : 0;
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $instansi->name }}</td>
                                <td class="px-6 py-4 text-center text-gray-600">{{ $totalFilled }} Kriteria</td>
                                <td class="px-6 py-4 text-center font-bold text-accent">{{ number_format($totalScore, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="openModal('periksaModal-{{ $instansi->id }}', 'modalBoxPeriksa-{{ $instansi->id }}')" class="bg-maroon hover:bg-red-900 text-white transition px-4 py-2 rounded-lg text-sm font-medium shadow-md flex items-center gap-2 mx-auto">
                                        <i class="fas fa-search-plus"></i> Periksa LKE
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Belum ada instansi binaan yang ditugaskan kepada Anda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    @foreach($institutions as $instansi)
        @php
            $evals = $allEvaluations->get($instansi->id);
            $totalScore = $evals ? $evals->sum('final_score') : 0;
        @endphp
        <div id="periksaModal-{{ $instansi->id }}" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col h-[90vh]" id="modalBoxPeriksa-{{ $instansi->id }}">
                
                <form action="{{ route('dashboard.evaluasiinstansi.store', $instansi->id) }}" method="POST" class="flex flex-col h-full">
                    @csrf
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white shadow-sm z-10">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Evaluasi LKE: <span class="text-accent">{{ $instansi->name }}</span></h3>
                            <p class="text-xs text-gray-500 mt-1">Nilai Mandiri Sementara: {{ number_format($totalScore, 2) }}</p>
                        </div>
                        <button type="button" onclick="closeModal('periksaModal-{{ $instansi->id }}', 'modalBoxPeriksa-{{ $instansi->id }}')" class="text-gray-400 hover:text-red-500 transition bg-gray-100 hover:bg-red-50 w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto flex-1 bg-gray-50">
                        @if($evals)
                            @foreach($components as $component)
                                @foreach($component->subComponents as $sub)
                                    @foreach($sub->criteria as $criteria)
                                        @php 
                                            $eval = $evals->where('lke_criteria_id', $criteria->id)->first(); 
                                            if(!$eval) continue;
                                        @endphp
                                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                                            <div class="bg-gray-800 px-4 py-3 text-white flex justify-between items-center">
                                                <div>
                                                    <span class="text-xs bg-gray-600 px-2 py-1 rounded mr-2 font-mono">{{ $sub->code }}={{ $criteria->criteria_number }}</span>
                                                    <span class="font-semibold text-sm">{{ $criteria->description }}</span>
                                                </div>
                                                <div class="text-xs font-medium bg-gray-700 px-3 py-1 rounded-full">Bobot: {{ $sub->weight ?? $component->weight }}</div>
                                            </div>

                                            <div class="p-5 flex flex-col lg:flex-row gap-6">
                                                <div class="w-full lg:w-1/2 border-r border-gray-100 pr-0 lg:pr-6">
                                                    <h4 class="text-sm font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2"><i class="fas fa-building text-gray-400 mr-2"></i>Data dari Instansi</h4>
                                                    
                                                    <div class="mb-4">
                                                        <p class="text-xs font-semibold text-gray-500 mb-2">Dokumen Evidence yang Ditautkan:</p>
                                                        @forelse($eval->documents as $doc)
                                                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100 transition group mb-2">
                                                                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                                                <div class="flex-1">
                                                                    <p class="text-sm font-semibold text-gray-800 group-hover:text-accent transition truncate">{{ $doc->name }}</p>
                                                                </div>
                                                                <i class="fas fa-external-link-alt text-gray-400 group-hover:text-accent"></i>
                                                            </a>
                                                        @empty
                                                            <p class="text-xs text-red-500 italic">Tidak ada dokumen dilampirkan.</p>
                                                        @endforelse
                                                    </div>

                                                    <div>
                                                        <p class="text-xs font-semibold text-gray-500 mb-2">Penilaian Mandiri:</p>
                                                        <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 px-4 py-2 rounded-lg">
                                                            <span class="text-lg font-bold text-green-700">{{ $eval->predicate }}</span>
                                                            <span class="text-xs text-green-600">(Skor: {{ $eval->final_score }})</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="w-full lg:w-1/2">
                                                    <h4 class="text-sm font-bold text-maroon mb-3 border-b border-red-100 pb-2"><i class="fas fa-check-double text-maroon mr-2"></i>Evaluasi & Validasi Inspektorat</h4>
                                                    
                                                    <div class="mb-4">
                                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Sesuaikan Nilai (Jika Diperlukan):</label>
                                                        <select name="evaluations[{{ $eval->id }}][predicate]" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm font-medium text-gray-800">
                                                            @foreach(['AA', 'A', 'BB', 'B', 'CC', 'C', 'D', 'E'] as $pred)
                                                                <option value="{{ $pred }}" {{ $eval->predicate == $pred ? 'selected' : '' }}>Predikat {{ $pred }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Catatan Perbaikan / Rekomendasi:</label>
                                                        <textarea name="evaluations[{{ $eval->id }}][notes]" rows="3" placeholder="Tambahkan catatan revisi jika dokumen kurang lengkap..." class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm text-gray-700">{{ $eval->inspector_notes }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endif
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 bg-white flex justify-between items-center z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                        <button type="submit" name="status_akhir" value="revisi" class="px-4 py-2 border border-maroon text-maroon hover:bg-red-50 rounded-lg text-sm font-bold transition flex items-center gap-2">
                            <i class="fas fa-undo"></i> Kembalikan untuk Revisi
                        </button>
                        <button type="submit" name="status_akhir" value="disetujui" class="bg-accent hover:bg-teal-700 text-white px-6 py-2 rounded-lg text-sm font-bold transition shadow-md flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Selesai & Setujui
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endforeach

    <script>
        function openModal(modalId, boxId) {
            const modal = document.getElementById(modalId);
            const modalBox = document.getElementById(boxId); // <-- Membaca Box secara dinamis
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);
        }

        function closeModal(modalId, boxId) {
            const modal = document.getElementById(modalId);
            const modalBox = document.getElementById(boxId);
            
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    </script>
@endsection