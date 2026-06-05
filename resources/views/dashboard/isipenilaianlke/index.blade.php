@extends('layouts.dashboard')

@section('content')
    <div class="flex-1 flex flex-col h-full relative">
        
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Pengisian LKE AKIP - Tahun {{ $year }}</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">Progres Pengisian:</span>
                <div class="w-32 bg-gray-200 rounded-full h-2.5">
                    <div class="bg-accent h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
                <span class="text-sm font-bold text-accent">{{ $progress }}%</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50 relative">
            
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-lg text-sm font-bold flex justify-between items-center">
                    <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-200 text-red-700 rounded-lg text-sm font-bold flex justify-between items-center">
                    <span><i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}</span>
                    <button onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Lembar Kerja Evaluasi</h2>
                    <p class="text-gray-500 mt-1 text-sm">Tautkan dokumen dari Bank Dokumen dan isi penilaian mandiri Anda.</p>
                </div>
                <button onclick="openModal('submitModal', 'modalBoxSubmit')" class="bg-maroon hover:bg-red-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-md flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Ajukan ke Inspektorat
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm w-1/2">Komponen / Sub-Komponen / Kriteria</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm w-1/4">Dokumen Ditautkan</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Nilai Mandiri</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @forelse($components as $component)
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <td colspan="4" class="px-6 py-3 font-bold text-gray-800">
                                    {{ $component->component_number }}. {{ $component->name }} 
                                    <span class="text-xs text-gray-500 ml-2 font-normal">(Bobot Maks: {{ $component->weight }})</span>
                                </td>
                            </tr>

                            @foreach($component->subComponents as $sub)
                                <tr class="bg-teal-50/30 border-b border-gray-100">
                                    <td colspan="4" class="px-6 py-2 pl-10 font-semibold text-gray-700 text-sm">
                                        {{ $sub->code }} {{ $sub->name }}
                                    </td>
                                </tr>

                                @foreach($sub->criteria as $criteria)
                                    @php 
                                        // Ambil evaluasi untuk kriteria ini
                                        $eval = $criteria->evaluations->first(); 
                                    @endphp
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        
                                        <td class="px-6 py-4 pl-14 align-top">
                                            <div class="flex gap-2">
                                                <span class="text-sm font-bold text-gray-600">{{ $criteria->criteria_number }}.</span>
                                                <div>
                                                    <p class="text-sm text-gray-800">{{ $criteria->description }}</p>
                                                    <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                        <p class="text-xs font-semibold text-accent mb-1"><i class="fas fa-info-circle"></i> Evidence yang diminta:</p>
                                                        <p class="text-xs text-gray-600 leading-relaxed">{{ $criteria->expected_evidence }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 align-top">
                                            <div class="space-y-2">
                                                @if($eval && $eval->documents->count() > 0)
                                                    @foreach($eval->documents as $doc)
                                                        <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 border border-gray-200 text-xs font-medium text-gray-700 w-full hover:bg-gray-200 transition truncate mb-1">
                                                            <i class="fas fa-file-pdf text-red-500"></i> {{ \Illuminate\Support\Str::limit($doc->name, 25) }}
                                                        </a>
                                                    @endforeach
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Belum ada dokumen tertaut</span>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 align-top text-center">
                                            @if($eval && $eval->predicate)
                                                <span class="px-3 py-1 bg-green-100 text-green-700 font-bold rounded-full text-sm">{{ $eval->predicate }}</span>
                                                <p class="text-xs text-gray-400 mt-1">Skor: {{ $eval->final_score }}</p>
                                            @else
                                                <span class="text-xs text-gray-400 italic">-</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 align-top text-center">
                                            @if($eval && $eval->predicate)
                                                <button onclick="openModal('evalModal-{{ $criteria->id }}', 'modalBoxEval-{{ $criteria->id }}')" class="text-gray-500 border border-gray-300 bg-white hover:text-accent hover:border-accent transition px-4 py-1.5 rounded text-sm flex items-center gap-2 mx-auto shadow-sm">
                                                    <i class="fas fa-edit"></i> Ubah
                                                </button>
                                            @else
                                                <button onclick="openModal('evalModal-{{ $criteria->id }}', 'modalBoxEval-{{ $criteria->id }}')" class="bg-accent hover:bg-teal-700 text-white transition px-4 py-1.5 rounded text-sm flex items-center gap-2 mx-auto shadow-sm">
                                                    <i class="fas fa-plus"></i> Lengkapi
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <div id="evalModal-{{ $criteria->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
                                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxEval-{{ $criteria->id }}">
                                            
                                            <form action="{{ route('dashboard.isipenilaianlke.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="lke_criteria_id" value="{{ $criteria->id }}">
                                                
                                                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                                    <h3 class="text-lg font-bold text-gray-800">Penilaian Mandiri: {{ $sub->code }}.{{ $criteria->criteria_number }}</h3>
                                                    <button type="button" onclick="closeModal('evalModal-{{ $criteria->id }}', 'modalBoxEval-{{ $criteria->id }}')" class="text-gray-400 hover:text-red-500 transition">
                                                        <i class="fas fa-times text-xl"></i>
                                                    </button>
                                                </div>

                                                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                                                    
                                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                                        <p class="text-sm font-semibold text-gray-800 mb-1">{{ $criteria->description }}</p>
                                                        <p class="text-xs text-gray-600">Evidence diminta: {{ $criteria->expected_evidence }}</p>
                                                    </div>

                                                    <div>
                                                        <div class="flex justify-between items-center mb-2">
                                                            <label class="block text-sm font-bold text-gray-800"><i class="fas fa-link text-accent mr-1"></i> Tautkan Dokumen dari Bank</label>
                                                            <a href="{{ route('dashboard.bankdokumen.index') }}" class="text-xs text-maroon hover:underline font-semibold">+ Buka Bank Dokumen</a>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 max-h-40 overflow-y-auto space-y-2">
                                                            @forelse($documents as $doc)
                                                                @php 
                                                                    // Cek apakah dokumen ini sudah pernah ditautkan
                                                                    $isChecked = $eval && $eval->documents->contains($doc->id); 
                                                                @endphp
                                                                <label class="flex items-center p-2 rounded hover:bg-white transition cursor-pointer border border-transparent hover:border-gray-200">
                                                                    <input type="checkbox" name="document_ids[]" value="{{ $doc->id }}" {{ $isChecked ? 'checked' : '' }} class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent accent-accent">
                                                                    <span class="ml-3 text-sm font-medium text-gray-700 truncate"><i class="fas fa-file-pdf text-red-500 mr-1"></i> {{ $doc->name }} ({{ $doc->year }})</span>
                                                                </label>
                                                            @empty
                                                                <p class="text-xs text-gray-400 text-center py-2">Belum ada dokumen di Bank Dokumen Instansi Anda.</p>
                                                            @endforelse
                                                        </div>
                                                        <p class="text-[10px] text-gray-400 mt-1"><i class="fas fa-info-circle"></i> Anda dapat mencentang lebih dari satu dokumen (Multiple Evidence).</p>
                                                    </div>

                                                    <div class="border-t border-gray-100 pt-5">
                                                        <label class="block text-sm font-bold text-gray-800 mb-2"><i class="fas fa-star-half-alt text-accent mr-1"></i> Predikat Penilaian Mandiri <span class="text-red-500">*</span></label>
                                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                            @foreach(['AA' => '100% (Inovatif)', 'A' => '90% (Sangat Baik)', 'BB' => '80% (Baik)', 'B' => '70% (Cukup)', 'CC' => '60% (Kurang)', 'C' => '50% (Sangat Kurang)'] as $pred => $desc)
                                                                <label class="cursor-pointer">
                                                                    <input type="radio" name="predikat" required class="peer sr-only" value="{{ $pred }}" {{ ($eval && $eval->predicate == $pred) ? 'checked' : '' }}>
                                                                    <div class="text-center p-2 border border-gray-200 rounded-lg peer-checked:bg-teal-50 peer-checked:border-accent peer-checked:text-accent hover:bg-gray-50 transition">
                                                                        <div class="font-bold text-sm">{{ $pred }}</div>
                                                                        <div class="text-[10px] text-gray-500 mt-1">{{ $desc }}</div>
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        <p class="text-[11px] text-gray-400 mt-2"><i class="fas fa-info-circle"></i> Nilai akhir (angka) otomatis dihitung oleh sistem berdasarkan bobot maksimal komponen.</p>
                                                    </div>

                                                </div>

                                                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                                                    <button type="button" onclick="closeModal('evalModal-{{ $criteria->id }}', 'modalBoxEval-{{ $criteria->id }}')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Batal</button>
                                                    <button type="submit" class="bg-accent hover:bg-teal-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition shadow-md">Simpan Data</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Template LKE belum dikonfigurasi oleh Inspektorat.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </main>

        <div id="submitModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxSubmit">
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-paper-plane text-2xl text-maroon"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Ajukan Evaluasi?</h3>
                    <p class="text-sm text-gray-500 mb-6">Pastikan semua dokumen evidence telah tertaut dan predikat sudah diisi. Anda tidak dapat mengubah data setelah diajukan ke Inspektorat.</p>
                    
                    <div class="flex justify-center gap-3">
                        <button onclick="closeModal('submitModal', 'modalBoxSubmit')" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Kembali</button>
                        <button onclick="alert('Fitur Submit sedang dalam pengembangan'); closeModal('submitModal', 'modalBoxSubmit')" class="bg-maroon hover:bg-red-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-md">Ya, Ajukan Sekarang</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

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