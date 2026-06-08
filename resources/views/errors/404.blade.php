<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | SI-AKSEL</title>
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
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-teal-50 rounded-full opacity-50"></div>
        
        <div class="relative z-10">
            <div class="w-24 h-24 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-teal-100 text-accent">
                <i class="fas fa-compass text-4xl animate-spin" style="animation-duration: 10s;"></i>
            </div>

            <h1 class="text-7xl font-black text-gray-900 tracking-tight">404</h1>
            <h2 class="text-xl font-bold text-gray-800 mt-4">Halaman Tidak Ditemukan</h2>
            <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                Maaf, halaman atau rute dashboard SI-AKSEL yang Anda cari tidak tersedia atau telah dipindahkan oleh Administrator.
            </p>

            <div class="mt-8 flex flex-col gap-2">
                <a href="{{ url('/dashboard/home') }}" class="w-full bg-accent hover:bg-teal-700 text-white font-bold py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-home"></i> Kembali ke Dashboard
                </a>
                <a href="{{ url('/') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-3 rounded-xl transition text-sm">
                    Menuju Landing Page
                </a>
            </div>
        </div>
    </div>

</body>
</html>