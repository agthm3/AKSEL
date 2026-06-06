@extends('layouts.dashboard')

@section('content')

    <div class="flex-1 flex flex-col h-full relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Manajemen Akun & Hak Akses</h1>
            <button onclick="openModal('userModal', 'modalBoxUser')" class="bg-maroon hover:bg-red-900 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-md transition flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Tambah Pengguna
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50 relative">
            
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-200 text-red-700 rounded-lg text-sm font-bold flex justify-between items-center">
                    <span><i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}</span>
                    <button onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Pengguna Terdaftar</h2>
                    <p class="text-gray-500 mt-1 text-sm">Kelola akun login dan tugaskan evaluator Inspektorat ke dinas binaannya.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Nama Lengkap & Email</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Peran (Role)</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Asal Instansi</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm w-1/4">Penugasan Evaluasi (Binaan)</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                        @forelse ($users as $user)
                            @php
                                $userRole = $user->roles->first();
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($userRole)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 font-bold rounded-full text-xs border border-blue-200">
                                            {{ str_replace('_', ' ', ucwords($userRole->name)) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $user->originInstitution ? $user->originInstitution->name : 'Belum Set Instansi' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->hasRole('operator_inspektorat'))
                                        @if($user->binaanInstitutions->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($user->binaanInstitutions as $binaan)
                                                    <span class="px-2 py-1 bg-teal-50 border border-teal-100 text-accent text-[10px] rounded font-semibold">{{ $binaan->alias ?? $binaan->name }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-red-500 italic font-semibold"><i class="fas fa-exclamation-triangle"></i> Belum ada dinas binaan</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-xs italic">Tidak berlaku</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center space-x-2 whitespace-nowrap">
                                    
                                    <!-- Tombol Assign (Hanya muncul jika rolenya operator_inspektorat) -->
                                    @if($user->hasRole('operator_inspektorat'))
                                        <button onclick="openModal('assignModal-{{ $user->id }}', 'modalBoxAssign-{{ $user->id }}')" class="text-xs bg-white border border-gray-300 text-gray-600 hover:text-accent hover:border-accent transition px-3 py-1.5 rounded font-medium shadow-sm" title="Atur Dinas Binaan">
                                            <i class="fas fa-link"></i>
                                        </button>
                                    @endif

                                    <!-- Tombol Edit -->
                                    <button onclick="openModal('editModal-{{ $user->id }}', 'modalBoxEdit-{{ $user->id }}')" class="text-xs bg-white border border-gray-300 text-gray-600 hover:text-blue-600 hover:border-blue-600 transition px-3 py-1.5 rounded font-medium shadow-sm" title="Edit Pengguna">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Tombol Hapus (Form) -->
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('dashboard.manajemenpengguna.destroy', $user->id) }}" method="POST" class="inline-block form-hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-hapus text-xs bg-white border border-gray-300 text-gray-400 hover:text-red-500 hover:border-red-500 transition px-3 py-1.5 rounded font-medium shadow-sm" data-nama="{{ $user->name }}" title="Hapus Pengguna">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data pengguna terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- ========================================== -->
    <!-- MODAL DINAMIS PER PENGGUNA                 -->
    <!-- ========================================== -->
    @foreach($users as $user)

        <!-- MODAL EDIT PENGGUNA -->
        <div id="editModal-{{ $user->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxEdit-{{ $user->id }}">
                <form action="{{ route('dashboard.manajemenpengguna.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-user-edit text-blue-600 mr-2"></i> Edit Pengguna</h3>
                        <button type="button" onclick="closeModal('editModal-{{ $user->id }}', 'modalBoxEdit-{{ $user->id }}')" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Peran (Role)</label>
                            @hasrole('super_admin')
                                <!-- Super Admin bisa ganti Role -->
                                <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm text-gray-700">
                                    <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin Umum</option>
                                    <option value="inspektorat" {{ $user->hasRole('inspektorat') ? 'selected' : '' }}>Pimpinan Inspektorat</option>
                                    <option value="operator_inspektorat" {{ $user->hasRole('operator_inspektorat') ? 'selected' : '' }}>Operator Inspektorat (Evaluator)</option>
                                    <option value="operator_dinas" {{ $user->hasRole('operator_dinas') ? 'selected' : '' }}>Operator Dinas</option>
                                </select>
                            @else
                                <!-- Admin biasa TIDAK bisa ganti Role (Readonly) -->
                                @php $roleName = $user->roles->first() ? str_replace('_', ' ', ucwords($user->roles->first()->name)) : 'Tidak ada'; @endphp
                                <input type="text" value="{{ $roleName }}" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-500 cursor-not-allowed">
                                <p class="text-[10px] text-gray-400 mt-1"><i class="fas fa-lock"></i> Hanya Super Admin yang dapat mengubah role akun.</p>
                            @endhasrole
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Asal Instansi</label>
                            <select name="institution_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm text-gray-700">
                                @foreach($institutions as $instansi)
                                    <option value="{{ $instansi->id }}" {{ $user->institution_id == $instansi->id ? 'selected' : '' }}>{{ $instansi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                        <button type="button" onclick="closeModal('editModal-{{ $user->id }}', 'modalBoxEdit-{{ $user->id }}')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white px-6 py-2 rounded-lg text-sm font-bold transition shadow-md">Update Profil</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL ASSIGN DINAS (Hanya di-render jika rolenya operator_inspektorat) -->
        @if($user->hasRole('operator_inspektorat'))
            <div id="assignModal-{{ $user->id }}" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[85vh]" id="modalBoxAssign-{{ $user->id }}">
                    <form action="{{ route('dashboard.manajemenpengguna.assign', $user->id) }}" method="POST" class="flex flex-col h-full">
                        @csrf
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 z-10">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-link text-accent mr-2"></i> Penugasan Evaluasi</h3>
                                <p class="text-xs text-gray-500 mt-1">Evaluator: <span class="font-bold text-maroon">{{ $user->name }}</span></p>
                            </div>
                            <button type="button" onclick="closeModal('assignModal-{{ $user->id }}', 'modalBoxAssign-{{ $user->id }}')" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
                        </div>
                        <div class="p-6 overflow-y-auto flex-1 bg-white">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 space-y-2">
                                @foreach($institutions as $inst)
                                    @php
                                        // Cek apakah instansi ini sudah masuk daftar binaan user
                                        $isChecked = $user->binaanInstitutions->contains($inst->id);
                                    @endphp
                                    <label class="flex items-center p-2 rounded hover:bg-white transition cursor-pointer border border-transparent hover:border-gray-200">
                                        <input type="checkbox" name="institution_ids[]" value="{{ $inst->id }}" {{ $isChecked ? 'checked' : '' }} class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent accent-accent">
                                        <span class="ml-3 text-sm font-medium text-gray-700">{{ $inst->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-[11px] text-gray-400 mt-3"><i class="fas fa-info-circle"></i> Beri centang pada instansi yang akan menjadi tanggung jawab evaluator ini.</p>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50 z-10">
                            <button type="button" onclick="closeModal('assignModal-{{ $user->id }}', 'modalBoxAssign-{{ $user->id }}')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Batal</button>
                            <button type="submit" class="bg-accent hover:bg-teal-700 text-white px-6 py-2 rounded-lg text-sm font-bold transition shadow-md">Simpan Penugasan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    @endforeach

    <!-- MODAL TAMBAH PENGGUNA (Utama) -->
    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxUser">
            <form action="{{ route('dashboard.manajemenpengguna.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-user-plus text-accent mr-2"></i> Tambah Pengguna Baru</h3>
                    <button type="button" onclick="closeModal('userModal', 'modalBoxUser')" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Peran (Role)</label>
                        <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm text-gray-700">
                            <option value="">-- Pilih Peran --</option>
                            <option value="admin">Admin Umum</option>
                            <option value="inspektorat">Pimpinan Inspektorat</option>
                            <option value="operator_inspektorat">Operator Inspektorat (Evaluator)</option>
                            <option value="operator_dinas">Operator Dinas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Asal Instansi</label>
                        <select name="institution_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm text-gray-700">
                            <option value="">-- Pilih Instansi --</option>
                            @foreach($institutions as $instansi)
                                <option value="{{ $instansi->id }}">{{ $instansi->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                    <button type="button" onclick="closeModal('userModal', 'modalBoxUser')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="bg-maroon hover:bg-red-900 text-white px-6 py-2 rounded-lg text-sm font-bold transition shadow-md">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT SWEETALERT2 & MODAL -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert2 untuk Konfirmasi Hapus Pengguna
        document.addEventListener('DOMContentLoaded', function () {
            const tombolHapus = document.querySelectorAll('.btn-hapus');
            
            tombolHapus.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    const namaData = this.getAttribute('data-nama');

                    Swal.fire({
                        title: 'Hapus Akun Pengguna?',
                        text: `Anda yakin ingin menghapus akun ${namaData}? Tindakan ini tidak dapat dibatalkan.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#800000',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: { popup: 'rounded-2xl shadow-xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });

        // Toast Notifikasi
        @if(session('success'))
            Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
            }).fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif

        // Fungsi Buka Tutup Modal Dinamis
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