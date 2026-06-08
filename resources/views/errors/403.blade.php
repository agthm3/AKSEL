<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | SI-AKSEL</title>
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
        <div class="absolute -top-10 -left-10 w-32 h-32 bg-yellow-50 rounded-full opacity-50"></div>
        
        <div class="relative z-10">
            <div class="w-24 h-24 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-yellow-100 text-yellow-600">
                <i class="fas fa-shield-alt text-3xl"></i>
            </div>

            <h1 class="text-7xl font-black text-gray-900 tracking-tight">403</h1>
            <h2 class="text-xl font-bold text-gray-800 mt-4">Hak Akses Dibatasi</h2>
            <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                Anda tidak memiliki wewenang atau hak akses khusus untuk membuka halaman modul ini. Silakan hubungi Admin BRIDA jika ini adalah kesalahan.
            </p>

            <div class="mt-8">
                <a href="{{ url('/dashboard/home') }}" class="w-full bg-accent hover:bg-teal-700 text-white font-bold py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali ke Menu Anda
                </a>
            </div>
        </div>
    </div>

</body>
</html>