<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Internal Server | SI-AKSEL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: '#1296b0',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen font-sans overflow-hidden">

    <div class="max-w-md w-full text-center p-8 bg-white rounded-2xl shadow-xl border border-gray-100 mx-4 relative overflow-hidden">
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-red-50 rounded-full opacity-50"></div>
        
        <div class="relative z-10">
            <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-100 text-red-500">
                <i class="fas fa-tools text-3xl animate-pulse"></i>
            </div>

            <h1 class="text-7xl font-black text-gray-900 tracking-tight">500</h1>
            <h2 class="text-xl font-bold text-gray-800 mt-4">Kesalahan Internal Server</h2>
            <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                Terjadi kendala teknis atau gangguan query database pada sistem kami. Tim teknis BRIDA sedang mendeteksi dan memperbaikinya.
            </p>

            <div class="mt-8">
                <button onclick="window.location.reload()" class="w-full bg-accent hover:bg-teal-700 text-white font-bold py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2 mb-2">
                    <i class="fas fa-sync-alt"></i> Muat Ulang Halaman (Refresh)
                </button>
                <a href="{{ url('/dashboard/home') }}" class="w-full block bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-3 rounded-xl transition text-sm">
                    Kembali ke Beranda Aman
                </a>
            </div>
        </div>
    </div>

</body>
</html>