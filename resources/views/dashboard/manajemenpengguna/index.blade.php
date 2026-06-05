@extends('layouts.dashboard')

@section('content')

    <div class="flex-1 flex flex-col h-full relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Manajemen Akun & Hak Akses</h1>
            <button onclick="openModal('userModal')" class="bg-maroon hover:bg-red-900 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-md transition flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Tambah Pengguna
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50 relative">
            
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Pengguna Terdaftar</h2>
                    <p class="text-gray-500 mt-1 text-sm">Kelola akun login dan tugaskan evaluator Inspektorat ke dinas binaannya.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row gap-4 justify-between items-center border border-gray-200">
                <div class="flex gap-2 w-full md:w-auto">
                    <button class="px-4 py-2 bg-accent text-white rounded-lg text-sm font-bold shadow-sm">Semua</button>
                    <button class="px-4 py-2 bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100 rounded-lg text-sm font-medium transition">Admin Inspektorat</button>
                    <button class="px-4 py-2 bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100 rounded-lg text-sm font-medium transition">Operator Dinas</button>
                </div>
                <div class="relative w-full md:w-64">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Cari nama atau email..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-accent text-sm">
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Nama Lengkap & Email</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Peran (Role)</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Asal Instansi</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Penugasan Evaluasi (Binaan)</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @foreach($user->roles as $role)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 font-bold rounded-full text-xs border border-blue-200">
                                            {{ str_replace('_', ' ', ucwords($role->name)) }}
                                        </span>
                                    @endforeach
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
                                <td class="px-6 py-4 text-center space-x-2">
                                    @if($user->hasRole('operator_inspektorat'))
                                        <button onclick="openModal('assignModal')" class="text-xs bg-white border border-gray-300 text-gray-600 hover:text-accent hover:border-accent transition px-3 py-1.5 rounded font-medium shadow-sm" title="Atur Dinas Binaan">
                                            <i class="fas fa-link mr-1"></i> Assign
                                        </button>
                                    @else
                                        <button class="text-gray-300 cursor-not-allowed text-xs border border-gray-200 px-3 py-1.5 rounded font-medium bg-gray-50" disabled>Assign</button>
                                    @endif
                                    <button class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-trash-alt"></i></button>
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

    <div id="assignModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[85vh]" id="modalBoxAssign">
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 z-10">
                <div>
                    <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-link text-accent mr-2"></i> Penugasan Evaluasi (Assign)</h3>
                    <p class="text-xs text-gray-500 mt-1">Pilih dinas yang akan diperiksa oleh: <span class="font-bold text-maroon">Andi Pratama</span></p>
                </div>
                <button onclick="closeModal('assignModal', 'modalBoxAssign')" class="text-gray-400 hover:text-red-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 bg-white">
                
                <div class="relative mb-4">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Cari nama dinas..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm">
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 max-h-64 overflow-y-auto space-y-2">
                    
                    <label class="flex items-center p-2 rounded hover:bg-white transition cursor-pointer border border-transparent hover:border-gray-200">
                        <input type="checkbox" class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent accent-accent" checked>
                        <span class="ml-3 text-sm font-medium text-gray-700">Dinas Komunikasi dan Informatika</span>
                    </label>

                    <label class="flex items-center p-2 rounded hover:bg-white transition cursor-pointer border border-transparent hover:border-gray-200">
                        <input type="checkbox" class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent accent-accent" checked>
                        <span class="ml-3 text-sm font-medium text-gray-700">Dinas Pekerjaan Umum</span>
                    </label>

                    <label class="flex items-center p-2 rounded hover:bg-white transition cursor-pointer border border-transparent hover:border-gray-200">
                        <input type="checkbox" class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent accent-accent">
                        <span class="ml-3 text-sm font-medium text-gray-700">BRIDA Kota Makassar</span>
                    </label>

                    <label class="flex items-center p-2 rounded hover:bg-white transition cursor-pointer border border-transparent hover:border-gray-200">
                        <input type="checkbox" class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent accent-accent">
                        <span class="ml-3 text-sm font-medium text-gray-700">Dinas Sosial</span>
                    </label>

                    <label class="flex items-center p-2 rounded hover:bg-white transition cursor-pointer border border-transparent hover:border-gray-200">
                        <input type="checkbox" class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent accent-accent">
                        <span class="ml-3 text-sm font-medium text-gray-700">Dinas Pendidikan</span>
                    </label>
                    
                    <label class="flex items-center p-2 rounded hover:bg-white transition cursor-pointer border border-transparent hover:border-gray-200">
                        <input type="checkbox" class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent accent-accent" disabled>
                        <span class="ml-3 text-sm font-medium text-gray-400">Dinas Kesehatan <span class="text-[10px] text-red-500 ml-1">(Sudah di-assign ke evaluator lain)</span></span>
                    </label>

                </div>
                <p class="text-[11px] text-gray-400 mt-3"><i class="fas fa-info-circle"></i> Evaluator hanya dapat melihat, mengevaluasi, dan menyetujui LKE dari instansi yang dicentang di atas.</p>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50 z-10">
                <button onclick="closeModal('assignModal', 'modalBoxAssign')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">
                    Batal
                </button>
                <button onclick="closeModal('assignModal', 'modalBoxAssign')" class="bg-accent hover:bg-teal-700 text-white px-6 py-2 rounded-lg text-sm font-bold transition shadow-md">
                    Simpan Penugasan
                </button>
            </div>
        </div>
    </div>

    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxUser">
            
            <form action="{{ route('dashboard.manajemenpengguna.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-user-plus text-accent mr-2"></i> Tambah Pengguna Baru</h3>
                    <button type="button" onclick="closeModal('userModal', 'modalBoxUser')" class="text-gray-400 hover:text-red-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
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

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            const boxId = modalId === 'assignModal' ? 'modalBoxAssign' : 'modalBoxUser';
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