<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-AKSEL Dashboard Prototype</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: '#800000',
                        accent: '#1296b0',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden font-sans">

    <aside class="w-72 bg-white shadow-xl flex flex-col h-full">
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <div class="text-2xl font-bold text-accent flex items-center gap-2">
                <i class="fas fa-chart-line"></i> SI-AKSEL
            </div>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-6">
            
            <div>
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Umum</p>
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-3 py-2 text-white bg-accent rounded-lg mb-1">
                    <i class="fas fa-home w-5"></i> Dashboard
                </a>
            </div>

            <div>
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Dinas (Operator)</p>
                <a href="{{ route('dashboard.bankdokumen.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-maroon hover:bg-red-50 rounded-lg transition mb-1">
                    <i class="fas fa-folder-open w-5"></i> Bank Dokumen Evidence
                </a>
                <a href="{{ route('dashboard.isipenilaianlke.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-maroon hover:bg-red-50 rounded-lg transition mb-1">
                    <i class="fas fa-edit w-5"></i> Isi Penilaian LKE
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-maroon hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-history w-5"></i> Riwayat Evaluasi
                </a>
            </div>

            <div>
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Inspektorat</p>
                <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-maroon hover:bg-red-50 rounded-lg transition mb-1">
                    <i class="fas fa-file-signature w-5"></i> Evaluasi Instansi (Pemeriksaan)
                </a>
                <a href="{{ route('dashboard.kelolatemplatelke.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-maroon hover:bg-red-50 rounded-lg transition mb-1">
                    <i class="fas fa-layer-group w-5"></i> Kelola Template LKE
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-maroon hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-chart-bar w-5"></i> Rekapitulasi Nilai Akhir
                </a>
            </div>

            <div>
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Super Admin</p>
                <a href="{{ route('dashboard.manajemenpengguna.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-maroon hover:bg-red-50 rounded-lg transition mb-1">
                    <i class="fas fa-users w-5"></i> Manajemen Pengguna
                </a>
                <a href="{{ route('dashboard.masterinstansi.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:text-maroon hover:bg-red-50 rounded-lg transition mb-1">
                    <i class="fas fa-building w-5"></i> Master Instansi
                </a>
            </div>
        </div>
        
        <div class="border-t border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center text-white font-bold">
                    F
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Fadehl Thristansyah</p>
                    <p class="text-xs text-gray-500">Super Admin - BRIDA</p>
                </div>
            </div>
        </div>
    </aside>


      @yield('content')

</body>
</html>