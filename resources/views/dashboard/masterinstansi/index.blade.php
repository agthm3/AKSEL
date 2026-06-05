@extends('layouts.dashboard')

@section('content')

    <div class="flex-1 flex flex-col h-full relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Manajemen Data Instansi</h1>
            <button onclick="openModal('instansiModal')" class="bg-maroon hover:bg-red-900 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Tambah Instansi
            </button>
        </header>

    <main class="flex-1 overflow-y-auto p-8 bg-gray-50 relative">
            
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-lg text-sm font-bold flex justify-between items-center">
                    <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Perangkat Daerah (OPD)</h2>
                    <p class="text-gray-500 mt-1 text-sm">Kelola daftar instansi di lingkungan Pemerintah Kota Makassar yang menjadi subjek evaluasi AKIP.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-accent flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Instansi Aktif</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalAktif }} <span class="text-sm font-medium text-gray-400">OPD</span></h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center text-accent text-xl">
                        <i class="fas fa-city"></i>
                    </div>
                </div>

                <div class="md:col-span-3 bg-white p-4 rounded-xl shadow-sm flex gap-4 items-center border border-gray-200">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
                        <input type="text" placeholder="Cari nama instansi atau singkatan..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-accent text-sm">
                    </div>
                    <select class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-accent text-sm text-gray-600">
                        <option>Status: Semua</option>
                        <option>Status: Aktif</option>
                        <option>Status: Non-Aktif</option>
                    </select>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm w-16 text-center">No</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Nama Lengkap Instansi</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Singkatan / Alias</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Jumlah Akun User</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                        
                        @forelse ($institutions as $instansi)
                            <tr class="hover:bg-gray-50 transition {{ $instansi->status == 'nonaktif' ? 'bg-gray-50/50' : '' }}">
                                <td class="px-6 py-4 text-center {{ $instansi->status == 'nonaktif' ? 'text-gray-400' : 'text-gray-500' }}">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-bold text-gray-800 {{ $instansi->status == 'nonaktif' ? 'line-through decoration-gray-300 text-gray-400' : '' }}">{{ $instansi->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-gray-100 {{ $instansi->status == 'nonaktif' ? 'text-gray-400' : 'text-gray-600' }} font-mono text-xs rounded border border-gray-200">{{ $instansi->alias }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="{{ $instansi->status == 'nonaktif' ? 'text-gray-400' : 'text-accent' }} font-bold"><i class="fas fa-users mr-1"></i> {{ $instansi->users_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($instansi->status == 'aktif')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 font-bold rounded-full text-xs">Aktif</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-200 text-gray-500 font-bold rounded-full text-xs">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <button class="text-gray-400 hover:text-accent transition"><i class="fas fa-edit"></i></button>
                                    @if($instansi->users_count > 0)
                                        <button class="text-gray-300 cursor-not-allowed transition" disabled title="Instansi yang memiliki user tidak dapat dihapus"><i class="fas fa-trash-alt"></i></button>
                                    @else
                                        <button class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-trash-alt"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data instansi terdaftar.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <div id="instansiModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300" id="modalBoxInstansi">
            
            <form action="{{ route('dashboard.masterinstansi.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-building text-accent mr-2"></i> Form Data Instansi</h3>
                    <button type="button" onclick="closeModal('instansiModal', 'modalBoxInstansi')" class="text-gray-400 hover:text-red-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap Instansi <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Dinas Kesehatan Kota Makassar" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent text-sm text-gray-800">
                        <p class="text-[11px] text-gray-400 mt-1">Nama ini akan tercetak di Laporan Hasil Evaluasi (LHE).</p>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Singkatan / Alias <span class="text-red-500">*</span></label>
                            <input type="text" name="alias" required placeholder="Contoh: DINKES" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent uppercase text-sm text-gray-800">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Status Instansi</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm text-gray-800 bg-white">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100 mt-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="2" placeholder="Informasi terkait perubahan nomenklatur instansi, dll..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent text-sm text-gray-700"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                    <button type="button" onclick="closeModal('instansiModal', 'modalBoxInstansi')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="bg-accent hover:bg-teal-700 text-white px-6 py-2 rounded-lg text-sm font-bold transition shadow-md">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalBox = document.getElementById('modalBoxInstansi');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);
        }

        function closeModal(modalId, boxId) {
            const modal = document.getElementById(modalId);
            const modalBox = document.getElementById('modalBoxInstansi');
            
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    </script>

@endsection