@extends('layouts.dashboard')

@section('content')
    <div class="flex-1 flex flex-col h-full relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Rekapitulasi Hasil Evaluasi AKIP</h1>
            <button onclick="openModal('exportModal')" class="bg-maroon hover:bg-red-900 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-md transition flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Ekspor Laporan
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50 relative">
            
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Tahun Evaluasi: {{ $year }}</h2>
                    <p class="text-gray-500 mt-1 text-sm">Pemantauan progres dan hasil akhir penilaian performa instansi tingkat Kota Makassar.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-accent">
                    <p class="text-sm text-gray-500 font-medium">Rata-rata Nilai Kota Makassar</p>
                    <div class="flex items-end gap-3 mt-2">
                        <h3 class="text-4xl font-bold text-gray-800">{{ number_format($rataRataKota, 2) }}</h3>
                        <span class="px-3 py-1 bg-teal-50 text-accent font-bold rounded-full text-sm border border-teal-100 mb-1">{{ $rataRataPredikat }}</span>
                    </div>
                    <p class="text-xs text-green-600 mt-4 font-medium"><i class="fas fa-info-circle mr-1"></i> Berdasarkan nilai instansi yang disetujui.</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-maroon">
                    <p class="text-sm text-gray-500 font-medium">Progres Evaluasi Inspektorat</p>
                    <div class="flex items-end gap-3 mt-2">
                        <h3 class="text-4xl font-bold text-gray-800">{{ $instansiSelesai }}<span class="text-xl text-gray-400 font-normal">/{{ $totalInstitutions }}</span></h3>
                        <span class="text-sm text-gray-500 mb-1 font-medium">Instansi Selesai</span>
                    </div>
                    <div class="mt-4 w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-maroon h-1.5 rounded-full" style="width: {{ $progressPersen }}%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-yellow-500">
                    <p class="text-sm text-gray-500 font-medium">Distribusi Predikat Terbanyak</p>
                    <div class="flex gap-4 mt-3 overflow-x-auto pb-1">
                        @foreach(['AA', 'A', 'BB', 'B'] as $pred)
                            <div class="text-center min-w-[3.5rem]">
                                <div class="text-2xl font-bold {{ $pred == 'BB' ? 'text-accent' : 'text-gray-800' }}">{{ $predikatCounts[$pred] }}</div>
                                <div class="text-xs {{ $pred == 'BB' ? 'text-accent font-bold bg-teal-50 px-2 rounded' : 'text-gray-500 font-semibold' }} mt-1">{{ $pred }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800"><i class="fas fa-list-ol text-gray-400 mr-2"></i> Peringkat Instansi</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-white border-b border-gray-200">
                                <th class="px-6 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider" rowspan="2">Peringkat</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider" rowspan="2">Nama Instansi</th>
                                <th class="px-6 py-2 font-semibold text-gray-600 text-xs text-center border-b border-gray-200" colspan="{{ $components->count() }}">Rincian Komponen</th>
                                <th class="px-6 py-4 font-bold text-gray-800 text-sm text-center bg-gray-50" rowspan="2">Total Nilai</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center" rowspan="2">Predikat</th>
                            </tr>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                @foreach($components as $comp)
                                    <th class="px-4 py-2 font-medium text-gray-500 text-[10px] text-center border-r border-gray-200" title="{{ $comp->name }} (Bobot {{ $comp->weight }})">
                                        {{ \Illuminate\Support\Str::limit($comp->name, 15) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            @forelse($rekapData as $index => $data)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    @if($data['status'] == 'Selesai')
                                        <td class="px-6 py-3 text-center font-bold">
                                            @if($index == 0)
                                                <span class="text-yellow-500"><i class="fas fa-medal text-lg"></i> 1</span>
                                            @elseif($index == 1)
                                                <span class="text-gray-400"><i class="fas fa-medal text-lg"></i> 2</span>
                                            @elseif($index == 2)
                                                <span class="text-yellow-700"><i class="fas fa-medal text-lg"></i> 3</span>
                                            @else
                                                <span class="text-gray-500">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 font-bold text-gray-800">{{ $data['institution_name'] }}</td>
                                        
                                        @foreach($components as $comp)
                                            <td class="px-4 py-3 text-center text-gray-600 border-r border-gray-100">
                                                {{ number_format($data['component_scores'][$comp->id], 2) }}
                                            </td>
                                        @endforeach

                                        <td class="px-6 py-3 text-center font-bold text-xl text-gray-800 bg-gray-50/50">{{ number_format($data['total_score'], 2) }}</td>
                                        <td class="px-6 py-3 text-center">
                                            @php
                                                $badgeClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                                if($data['predikat'] == 'AA') $badgeClass = 'bg-green-100 text-green-700 border-green-200';
                                                if($data['predikat'] == 'A') $badgeClass = 'bg-teal-50 text-accent border-teal-200';
                                                if($data['predikat'] == 'BB') $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                                if($data['predikat'] == 'B') $badgeClass = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                            @endphp
                                            <span class="px-3 py-1 font-bold rounded-full text-xs border {{ $badgeClass }}">{{ $data['predikat'] }}</span>
                                        </td>
                                    @else
                                        <td class="px-6 py-3 text-center font-bold text-gray-400">-</td>
                                        <td class="px-6 py-3 font-medium text-gray-500">{{ $data['institution_name'] }}</td>
                                        <td colspan="{{ $components->count() }}" class="px-4 py-3 text-center text-gray-400 text-xs italic border-r border-gray-100">
                                            Belum Disetujui (Sedang Proses Evaluasi)
                                        </td>
                                        <td class="px-6 py-3 text-center font-bold text-gray-400 bg-gray-50/50">-</td>
                                        <td class="px-6 py-3 text-center">
                                            <span class="px-3 py-1 bg-gray-100 text-gray-500 font-bold rounded-full text-xs border border-gray-200">N/A</span>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $components->count() + 4 }}" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data evaluasi instansi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxExport">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-maroon text-white">
                <h3 class="text-lg font-bold"><i class="fas fa-file-export mr-2"></i> Ekspor Laporan LKE</h3>
                <button onclick="closeModal('exportModal')" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-5">
                <p class="text-sm text-gray-600 mb-4">Pilih format unduhan untuk Rekapitulasi Hasil Evaluasi AKIP Tahun {{ $year }}.</p>
                <div class="grid grid-cols-2 gap-4">
                    <button onclick="alert('Fitur Ekspor Excel sedang dikembangkan!'); closeModal('exportModal')" class="border border-green-500 bg-green-50 hover:bg-green-100 text-green-700 rounded-xl p-4 flex flex-col items-center justify-center transition group">
                        <i class="fas fa-file-excel text-4xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold text-sm">Microsoft Excel</span>
                    </button>
                    <button onclick="alert('Fitur Ekspor PDF sedang dikembangkan!'); closeModal('exportModal')" class="border border-red-500 bg-red-50 hover:bg-red-100 text-maroon rounded-xl p-4 flex flex-col items-center justify-center transition group">
                        <i class="fas fa-file-pdf text-4xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold text-sm">Dokumen PDF</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalBox = document.getElementById('modalBoxExport');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalBox = document.getElementById('modalBoxExport');
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    </script>
@endsection