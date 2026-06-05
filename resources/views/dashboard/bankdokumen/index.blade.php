@extends('layouts.dashboard')

@section('content')
    <div class="flex-1 flex flex-col h-full relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-semibold text-gray-800">Bank Dokumen Evidence</h1>
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

                @if ($errors->any())
                    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex justify-between items-start">
                        <div>
                            <p class="font-bold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Gagal Mengunggah Dokumen:</p>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button onclick="this.parentElement.style.display='none'"><i class="fas fa-times mt-1"></i></button>
                    </div>
                @endif
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Dokumen Instansi</h2>
                    <p class="text-gray-500 mt-1 text-sm">Pusat penyimpanan satu pintu untuk semua evidence LKE Anda.</p>
                </div>
                <button onclick="openModal('uploadModal')" class="bg-maroon hover:bg-red-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-md flex items-center gap-2">
                    <i class="fas fa-cloud-upload-alt"></i> Unggah Dokumen Baru
                </button>
            </div>

            <!-- Tabel Dokumen Dinamis -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                            <th class="px-6 py-4 font-medium w-12">No</th>
                            <th class="px-6 py-4 font-medium">Nama Dokumen</th>
                            <th class="px-6 py-4 font-medium">Tahun</th>
                            <th class="px-6 py-4 font-medium">Status Penggunaan</th>
                            <th class="px-6 py-4 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse($documents as $doc)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800">{{ $doc->name }}</div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-file-pdf text-red-500 mr-1"></i> PDF • Diunggah {{ $doc->created_at->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $doc->year }}</td>
                                <td class="px-6 py-4">
                                    @if($doc->evaluations_count > 0)
                                        <span class="px-3 py-1 rounded-full bg-blue-50 text-accent text-xs font-semibold border border-blue-100">
                                            <i class="fas fa-link mr-1"></i> Ditautkan di {{ $doc->evaluations_count }} Kriteria
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-medium border border-gray-200">
                                            Belum ditautkan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-gray-400 hover:text-accent transition inline-block" title="Lihat/Unduh Dokumen"><i class="fas fa-eye"></i></a>
                                    
                                    <form action="{{ route('dashboard.bankdokumen.destroy', $doc->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Hapus Dokumen" {{ $doc->evaluations_count > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash-alt {{ $doc->evaluations_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada dokumen yang diunggah ke Bank Dokumen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- MODAL POP-UP UNGGAH DOKUMEN -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300" id="modalBox">
            
            <!-- Tambahkan enctype="multipart/form-data" AGAR BISA UPLOAD FILE -->
            <form action="{{ route('dashboard.bankdokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-cloud-upload-alt text-accent mr-2"></i> Unggah Dokumen Evidence</h3>
                    <button type="button" onclick="closeModal('uploadModal')" class="text-gray-400 hover:text-red-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dokumen <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Cth: Perda Nomor 5 Tahun 2021 tentang RPJMD" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent text-sm">
                        <p class="text-xs text-gray-400 mt-1">Gunakan nama yang jelas agar mudah dicari saat mengisi LKE.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit Dokumen <span class="text-red-500">*</span></label>
                        <input type="number" name="year" required min="2000" max="2030" value="{{ date('Y') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Dokumen (PDF) <span class="text-red-500">*</span></label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-accent hover:bg-teal-50 transition cursor-pointer group overflow-hidden">
                            <!-- Input File yang transparan, menutupi seluruh area kotak putus-putus -->
                            <input type="file" name="file" required accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="document.getElementById('fileName').innerText = this.files[0].name">
                            
                            <i class="fas fa-file-upload text-4xl text-gray-400 mb-3 group-hover:text-accent transition"></i>
                            <p class="text-sm text-gray-600 font-medium" id="fileName">Klik untuk memilih file atau seret file ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">Maksimal ukuran file 10MB. Format wajib: .pdf</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                    <button type="button" onclick="closeModal('uploadModal')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-maroon hover:bg-red-900 text-white px-6 py-2 rounded-lg text-sm font-medium transition shadow-md">
                        Simpan & Unggah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalBox = document.getElementById('modalBox');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalBox = document.getElementById('modalBox');
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                // Reset nama file jika dibatalkan
                document.getElementById('fileName').innerText = 'Klik untuk memilih file atau seret file ke sini';
            }, 200);
        }
    </script>
@endsection
</div>

