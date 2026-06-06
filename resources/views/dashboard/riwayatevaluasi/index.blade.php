@extends('layouts.dashboard')

@section('content')
    <div class="flex-1 flex flex-col h-full relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Riwayat Evaluasi LKE</h1>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50 relative">
            
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Rekam Jejak Penilaian</h2>
                    <p class="text-gray-500 mt-1 text-sm">Lihat hasil akhir evaluasi AKIP instansi Anda dari tahun ke tahun.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Tahun Evaluasi</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Pembaruan Terakhir</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Nilai Akhir</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Predikat</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Status</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse($riwayat as $item)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $item['year'] }}</td>
                                <td class="px-6 py-4">{{ $item['date'] }}</td>
                                
                                @if($item['status'] == 'Selesai Evaluasi')
                                    <td class="px-6 py-4 font-bold text-gray-800">{{ number_format($item['total_score'], 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-teal-50 text-accent font-bold rounded-full text-sm border border-teal-100">{{ $item['predikat'] }}</span>
                                    </td>
                                @else
                                    <td class="px-6 py-4 italic text-gray-400">{{ number_format($item['total_score'], 2) }} (Sementara)</td>
                                    <td class="px-6 py-4 italic text-gray-400">-</td>
                                @endif

                                <td class="px-6 py-4">
                                    @if($item['status'] == 'Selesai Evaluasi')
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Selesai Evaluasi</span>
                                    @elseif($item['status'] == 'Revisi')
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Butuh Revisi</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Menunggu Diperiksa</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <button onclick="openModal('detailModal-{{ $item['year'] }}', 'modalBoxDetail-{{ $item['year'] }}')" class="text-accent hover:text-blue-800 transition px-3 py-1.5 border border-gray-300 rounded hover:border-accent text-sm bg-white" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                    <button class="text-gray-400 cursor-not-allowed px-3 py-1.5 border border-gray-200 rounded text-sm bg-gray-50" title="Cetak Belum Tersedia" disabled>
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada riwayat evaluasi LKE untuk instansi Anda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    @foreach($riwayat as $item)
        <div id="detailModal-{{ $item['year'] }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[85vh]" id="modalBoxDetail-{{ $item['year'] }}">
                
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-chart-bar text-accent mr-2"></i> Detail Hasil Penilaian Tahun {{ $item['year'] }}</h3>
                    <button type="button" onclick="closeModal('detailModal-{{ $item['year'] }}', 'modalBoxDetail-{{ $item['year'] }}')" class="text-gray-400 hover:text-red-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                    
                    <div class="flex items-center justify-between bg-teal-50 p-4 rounded-xl border border-teal-100">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Nilai Akuntabilitas Kinerja</p>
                            <h2 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($item['total_score'], 2) }}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 font-medium">Predikat</p>
                            <span class="inline-block mt-1 px-4 py-1 bg-white text-accent font-bold rounded-full text-xl border border-teal-200 shadow-sm">{{ $item['predikat'] }}</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2">Rincian per Komponen</h4>
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-gray-500 bg-gray-50">
                                    <th class="py-2 px-3 font-medium rounded-tl-lg">Komponen</th>
                                    <th class="py-2 px-3 font-medium text-center">Bobot Maks.</th>
                                    <th class="py-2 px-3 font-medium text-center rounded-tr-lg">Nilai Diperoleh</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-gray-100">
                                @foreach($item['components'] as $comp)
                                    <tr>
                                        <td class="py-3 px-3">{{ $comp['number'] }}. {{ $comp['name'] }}</td>
                                        <td class="py-3 px-3 text-center">{{ number_format($comp['max_weight'], 2) }}</td>
                                        <td class="py-3 px-3 text-center font-bold text-accent">{{ number_format($comp['score'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(!empty($item['notes']))
                        <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                            <h4 class="font-bold text-yellow-800 text-sm mb-2"><i class="fas fa-clipboard-list mr-1"></i> Catatan & Rekomendasi Inspektorat</h4>
                            <ul class="text-xs text-yellow-700 list-disc list-inside space-y-1">
                                @foreach($item['notes'] as $note)
                                    <li>{{ $note }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-end bg-gray-50">
                    <button type="button" onclick="closeModal('detailModal-{{ $item['year'] }}', 'modalBoxDetail-{{ $item['year'] }}')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function openModal(modalId, boxId) {
            const modal = document.getElementById(modalId);
            const modalBox = document.getElementById(boxId);
            
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