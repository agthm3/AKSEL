@extends('layouts.dashboard')

@section('content')
    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col h-full relative">
        <!-- HEADER -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Manajemen Template LKE</h1>
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold border border-gray-200 transition">
                <i class="fas fa-copy mr-1"></i> Duplikasi Template Tahun Lalu
            </button>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto p-8 bg-gray-50 relative">
            
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Struktur LKE Tahun 2026</h2>
                    <p class="text-gray-500 mt-1 text-sm">Susun komponen, sub-komponen, dan tentukan daftar evidence yang disyaratkan.</p>
                </div>
                <div class="flex gap-2">
                    <select class="px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm font-bold text-gray-700">
                        <option>Tahun 2026 (Aktif)</option>
                        <option>Tahun 2025 (Arsip)</option>
                    </select>
                    @hasanyrole('super_admin|admin|inspektorat')
                        <button onclick="openModal('componentModal', 'modalBoxComponent')" class="bg-maroon hover:bg-red-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-md flex items-center gap-2">
                            <i class="fas fa-plus-circle"></i> Tambah Komponen Utama
                        </button>
                    @endhasanyrole
                </div>
            </div>

            <!-- Struktur Berjenjang / Hierarki -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                
                @forelse($components as $component)
                    <!-- LEVEL 1: KOMPONEN -->
                    <div class="bg-gray-800 text-white px-6 py-4 flex justify-between items-center border-b border-gray-700 group/comp">
                        <div class="flex items-center gap-3">
                            <span class="bg-gray-600 px-3 py-1 rounded text-sm font-bold">{{ $component->component_number }}</span>
                            <h3 class="text-lg font-bold">{{ $component->name }}</h3>
                            <span class="text-xs bg-accent text-white px-2 py-0.5 rounded-full ml-2">Bobot: {{ $component->weight }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            @hasanyrole('super_admin|admin|inspektorat')
                                <button onclick="openModal('subComponentModal-{{ $component->id }}', 'modalBoxSub-{{ $component->id }}')" class="text-gray-300 hover:text-white transition bg-gray-700 px-3 py-1 rounded text-sm"><i class="fas fa-plus mr-1"></i> Sub-Komponen</button>
                                
                                <!-- Form Hapus Komponen Utama -->
                                <form action="{{ route('dashboard.kelolatemplatelke.destroyComponent', $component->id) }}" method="POST" class="form-hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-hapus text-gray-400 hover:text-red-400 transition text-sm p-1" data-nama="Komponen {{ $component->name }} (Semua data di bawahnya akan ikut terhapus!)">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="hidden">Hapus</span>
                                    </button>
                                </form>
                            @endhasanyrole
                        </div>
                    </div>

                    @forelse($component->subComponents as $sub)
                        <!-- LEVEL 2: SUB-KOMPONEN -->
                        <div class="bg-teal-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center pl-12 group/sub">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-accent">{{ $sub->code }}</span>
                                <h4 class="text-sm font-semibold text-gray-800">{{ $sub->name }}</h4>
                            </div>
                            <div class="flex items-center gap-4 text-sm">
                                @hasanyrole('super_admin|admin|inspektorat')
                                    <button onclick="openModal('kriteriaModal-{{ $sub->id }}', 'modalBoxKriteria-{{ $sub->id }}')" class="text-maroon font-semibold hover:underline transition"><i class="fas fa-plus text-xs"></i> Tambah Kriteria</button>
                                    
                                    <!-- Form Hapus Sub-Komponen -->
                                    <form action="{{ route('dashboard.kelolatemplatelke.destroySubComponent', $sub->id) }}" method="POST" class="form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-hapus text-gray-400 hover:text-red-500 transition" data-nama="Sub-Komponen {{ $sub->code }}">
                                            <i class="fas fa-trash-alt"></i>
                                            <span class="hidden">Hapus</span>
                                        </button>
                                    </form>
                                @endhasanyrole
                            </div>
                        </div>

                        <!-- LEVEL 3: KRITERIA -->
                        <div class="divide-y divide-gray-100">
                            @forelse($sub->criteria as $criteria)
                                <div class="px-6 py-4 pl-20 hover:bg-gray-50 transition flex justify-between items-start group">
                                    <div class="flex-1 pr-6">
                                        <div class="flex gap-2 mb-2">
                                            <span class="text-sm font-bold text-gray-500 w-6">{{ $criteria->criteria_number }}.</span>
                                            <p class="text-sm font-medium text-gray-800">{{ $criteria->description }}</p>
                                        </div>
                                        <div class="ml-8 p-3 bg-blue-50/50 rounded-lg border border-blue-100 inline-block w-full">
                                            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Evidence:</p>
                                            <p class="text-xs text-gray-700">{{ $criteria->expected_evidence }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @hasanyrole('super_admin|admin|inspektorat')
                                            <!-- Form Hapus Kriteria -->
                                            <form action="{{ route('dashboard.kelolatemplatelke.destroyCriteria', $criteria->id) }}" method="POST" class="form-hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-hapus p-2 text-gray-400 hover:text-red-500 bg-white rounded shadow-sm border border-gray-200 transition" data-nama="Kriteria Nomor {{ $criteria->criteria_number }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <span class="hidden">Hapus</span>
                                                </button>
                                            </form>
                                        @endhasanyrole
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-4 pl-20 text-sm text-gray-400 italic">Belum ada kriteria di sub-komponen ini.</div>
                            @endforelse
                        </div>

                        <!-- MODAL TAMBAH KRITERIA -->
                        <div id="kriteriaModal-{{ $sub->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxKriteria-{{ $sub->id }}">
                                <form action="{{ route('dashboard.kelolatemplatelke.storeCriteria') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="lke_sub_component_id" value="{{ $sub->id }}">
                                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-layer-group text-accent mr-2"></i> Tambah Kriteria Baru</h3>
                                        <button type="button" onclick="closeModal('kriteriaModal-{{ $sub->id }}', 'modalBoxKriteria-{{ $sub->id }}')" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div class="bg-teal-50 text-accent text-xs font-semibold px-4 py-2 rounded-lg border border-teal-100 mb-4">
                                            Induk: {{ $component->component_number }}. {{ $component->name }} > {{ $sub->code }} {{ $sub->name }}
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Urut Kriteria <span class="text-red-500">*</span></label>
                                            <input type="number" name="criteria_number" required class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Kriteria <span class="text-red-500">*</span></label>
                                            <textarea name="description" rows="2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Daftar Evidence yang Diminta <span class="text-red-500">*</span></label>
                                            <textarea name="expected_evidence" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm"></textarea>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                                        <button type="button" onclick="closeModal('kriteriaModal-{{ $sub->id }}', 'modalBoxKriteria-{{ $sub->id }}')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Batal</button>
                                        <button type="submit" class="bg-accent hover:bg-teal-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition shadow-md">Simpan Kriteria</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 pl-12 text-sm text-gray-400 italic bg-white">Belum ada sub-komponen.</div>
                    @endforelse

                    <!-- MODAL TAMBAH SUB-KOMPONEN -->
                    <div id="subComponentModal-{{ $component->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxSub-{{ $component->id }}">
                            <form action="{{ route('dashboard.kelolatemplatelke.storeSubComponent') }}" method="POST">
                                @csrf
                                <input type="hidden" name="lke_component_id" value="{{ $component->id }}">
                                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                    <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-sitemap text-accent mr-2"></i> Tambah Sub-Komponen</h3>
                                    <button type="button" onclick="closeModal('subComponentModal-{{ $component->id }}', 'modalBoxSub-{{ $component->id }}')" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div class="flex gap-4">
                                        <div class="w-1/3">
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                                            <input type="text" name="code" placeholder="Cth: 1.b" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                                        </div>
                                        <div class="w-2/3">
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Bobot (Opsional)</label>
                                            <input type="number" step="0.01" name="weight" placeholder="Cth: 15.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Sub-Komponen <span class="text-red-500">*</span></label>
                                        <textarea name="name" rows="2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm"></textarea>
                                    </div>
                                </div>
                                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                                    <button type="button" onclick="closeModal('subComponentModal-{{ $component->id }}', 'modalBoxSub-{{ $component->id }}')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Batal</button>
                                    <button type="submit" class="bg-accent hover:bg-teal-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition shadow-md">Simpan Sub-Komponen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 italic">Belum ada komponen LKE terdaftar. Silakan tambah komponen utama.</div>
                @endforelse

            </div>
        </main>
        
        <!-- MODAL TAMBAH KOMPONEN UTAMA -->
        <div id="componentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxComponent">
                <form action="{{ route('dashboard.kelolatemplatelke.storeComponent') }}" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-folder text-accent mr-2"></i> Tambah Komponen Utama</h3>
                        <button type="button" onclick="closeModal('componentModal', 'modalBoxComponent')" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex gap-4">
                            <div class="w-1/3">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nomor <span class="text-red-500">*</span></label>
                                <input type="number" name="component_number" placeholder="Cth: 2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                            </div>
                            <div class="w-2/3">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Bobot Maksimal <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="weight" placeholder="Cth: 30.00" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Komponen <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Cth: PENGUKURAN KINERJA" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm uppercase">
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                        <button type="button" onclick="closeModal('componentModal', 'modalBoxComponent')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="bg-maroon hover:bg-red-900 text-white px-6 py-2 rounded-lg text-sm font-bold transition shadow-md">Simpan Komponen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT SWEETALERT2 & MODAL -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // INTEGRASI SWEETALERT2 UNTUK PROSES HAPUS
        document.addEventListener('DOMContentLoaded', function () {
            const tombolHapus = document.querySelectorAll('.btn-hapus');
            
            tombolHapus.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    const namaData = this.getAttribute('data-nama');

                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: `Anda akan menghapus ${namaData}. Tindakan ini tidak dapat dibatalkan!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#800000', // Warna Maroon khas AKSEL
                        cancelButtonColor: '#6b7280', // Gray
                        confirmButtonText: 'Ya, Hapus Sekarang!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        customClass: {
                            popup: 'rounded-2xl shadow-xl'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Eksekusi form form-hapus jika user klik Ya
                        }
                    });
                });
            });
        });

        // NOTIFIKASI SUKSES DENGAN TOAST SWEETALERT (OPSIONAL NAMUN SANGAT BAGUS)
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
            });
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // MODAL TOGGLE SYSTEM
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