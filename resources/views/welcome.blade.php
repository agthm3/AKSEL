<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SI-AKSEL | Sistem Informasi Evaluasi AKIP Kota Makassar</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Tailwind CSS Custom Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        accent: '#1296b0', // Warna Sekunder Utama baru Anda
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-800 bg-white overflow-x-hidden">

    <!-- ================= NAVBAR (GLASSMORPHISM PUTIH-TEAL) ================= -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="w-10 h-10 bg-gradient-to-br from-accent to-teal-500 rounded-xl flex items-center justify-center shadow-lg transform hover:rotate-12 transition">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <span class="font-black text-2xl tracking-tight text-gray-900">SI-<span class="text-accent">AKSEL</span></span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8 font-medium text-sm text-gray-600">
                    <a href="#beranda" class="hover:text-accent transition">Beranda</a>
                    <a href="#tentang" class="hover:text-accent transition">Tentang Sistem</a>
                    <a href="#fitur" class="hover:text-accent transition">Fitur Unggulan</a>
                    <a href="#statistik" class="hover:text-accent transition">Statistik</a>
                </div>

                <!-- Login Button -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard/home') }}" class="bg-accent hover:bg-teal-700 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-teal-500/20 transition transform hover:-translate-y-0.5">
                                Masuk Dashboard <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 font-bold hover:text-accent transition text-sm">Masuk</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- ================= HERO SECTION DENGAN GRADASI PUTIH ELEGAN ================= -->
    <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-gradient-to-b from-teal-50/50 via-white to-white">
        <!-- Animated Background Blobs (Disesuaikan ke warna Teal & Soft Blue) -->
        <div class="absolute top-0 -left-4 w-72 h-72 bg-teal-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-sky-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-emerald-100 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob animation-delay-4000"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                
                <!-- Teks Hero -->
                <div class="w-full lg:w-1/2" data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-teal-50 border border-teal-100 text-accent text-xs font-bold mb-6">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-accent"></span>
                        </span>
                        Sistem Evaluasi Terintegrasi Kota Makassar
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-black text-gray-900 leading-[1.15] tracking-tight mb-6">
                        Tingkatkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-teal-500">Akuntabilitas</span> Kinerja Instansi Anda.
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        SI-AKSEL mempermudah proses penilaian mandiri, pelaporan evidence, dan pemantauan LKE secara real-time untuk mewujudkan tata kelola pemerintahan yang baik.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}" class="bg-accent hover:bg-teal-700 text-white px-8 py-3.5 rounded-full text-base font-bold shadow-xl shadow-teal-500/30 transition transform hover:-translate-y-1 text-center flex items-center justify-center gap-2">
                            Mulai Evaluasi <i class="fas fa-rocket"></i>
                        </a>
                        <a href="#fitur" class="bg-white hover:bg-gray-50 text-gray-700 px-8 py-3.5 rounded-full text-base font-bold border border-gray-200 transition text-center flex items-center justify-center gap-2">
                            Pelajari Fitur <i class="fas fa-play-circle text-accent"></i>
                        </a>
                    </div>
                </div>

                <!-- Ilustrasi Hero / Mockup -->
                <div class="w-full lg:w-1/2 relative" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <!-- Glass Panel Mockup -->
                    <div class="relative bg-white/70 backdrop-blur-xl border border-white p-2 rounded-2xl shadow-2xl transform rotate-2 hover:rotate-0 transition duration-500">
                        <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200 shadow-inner relative">
                            <!-- Header Mockup -->
                            <div class="h-8 bg-gray-200 flex items-center px-4 gap-1.5 border-b border-gray-300">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                            </div>
                            <!-- Body Mockup -->
                            <div class="p-6 bg-white">
                                <div class="flex justify-between items-center mb-6">
                                    <div class="h-4 w-32 bg-gray-200 rounded animate-pulse"></div>
                                    <div class="h-6 w-16 bg-teal-100 rounded-full border border-teal-200"></div>
                                </div>
                                <div class="space-y-4">
                                    <div class="h-2 w-full bg-gray-100 rounded"></div>
                                    <div class="h-2 w-5/6 bg-gray-100 rounded"></div>
                                    <div class="h-2 w-4/6 bg-gray-100 rounded"></div>
                                </div>
                                <div class="mt-8 grid grid-cols-2 gap-4">
                                    <div class="h-24 bg-teal-50/50 rounded-lg border border-teal-100 flex items-center justify-center">
                                        <i class="fas fa-chart-pie text-accent/30 text-3xl"></i>
                                    </div>
                                    <div class="h-24 bg-emerald-50/50 rounded-lg border border-emerald-100 flex items-center justify-center">
                                        <i class="fas fa-file-signature text-emerald-600/30 text-3xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-xl border border-gray-100 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-accent text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold">Predikat Kota</p>
                                <p class="text-lg font-black text-gray-900">Sangat Baik (A)</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- ================= LOGO / PARTNERS ================= -->
    <section id="tentang" class="py-12 border-y border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center" data-aos="fade-up">
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Diinisiasi & Dikembangkan Oleh</p>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-20 opacity-70 grayscale hover:grayscale-0 transition duration-300">
                <div class="flex items-center gap-3">
                    <i class="fas fa-landmark text-3xl text-gray-700"></i>
                    <span class="font-black text-xl text-gray-700">BRIDA KOTA MAKASSAR</span>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-shield-alt text-3xl text-gray-700"></i>
                    <span class="font-black text-xl text-gray-700">INSPEKTORAT DAERAH</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FITUR UNGGULAN (PURE WHITE CARDS) ================= -->
    <section id="fitur" class="py-24 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-sm font-bold text-accent tracking-widest uppercase mb-2">Kenapa SI-AKSEL?</h2>
                <h3 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Fitur Lengkap untuk Kemudahan Evaluasi</h3>
                <p class="text-gray-600">Sistem dirancang spesifik untuk mengatasi kerumitan pengumpulan evidence fisik dan perhitungan berulang pada evaluasi AKIP.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-teal-50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-accent transition duration-300">
                        <i class="fas fa-cloud-upload-alt text-2xl text-accent group-hover:text-white transition"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Bank Dokumen Pintar</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Unggah sekali, gunakan berkali-kali. Sistem penyimpanan terpusat untuk semua evidence LKE instansi Anda agar mudah ditautkan ke kriteria manapun.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-teal-50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-accent transition duration-300">
                        <i class="fas fa-calculator text-2xl text-accent group-hover:text-white transition"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Penilaian Otomatis</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Tidak perlu lagi menghitung manual dengan Excel. Masukkan predikat penilaian mandiri, dan biarkan SI-AKSEL mengonversi bobot menjadi nilai akhir secara instan.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-teal-50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-accent transition duration-300">
                        <i class="fas fa-search-plus text-2xl text-accent group-hover:text-white transition"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Verifikasi Inspektorat</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Modul khusus bagi pemeriksa untuk meninjau evidence, menyetujui nilai, atau mengembalikan dokumen secara online disertai catatan revisi.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= STATISTIK (DARK TECH THEME) ================= -->
    <section id="statistik" class="py-24 bg-slate-900 relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute right-0 top-0 w-96 h-96 bg-accent rounded-full filter blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute left-0 bottom-0 w-96 h-96 bg-teal-600 rounded-full filter blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-3xl md:text-4xl font-black text-white mb-16" data-aos="zoom-in">Dampak Sistem Bagi Kota Makassar</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 divide-y md:divide-y-0 md:divide-x divide-slate-800">
                <div class="pt-6 md:pt-0" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-5xl font-black text-white mb-2">100<span class="text-accent">+</span></h3>
                    <p class="text-slate-400 font-medium uppercase tracking-wider text-sm">Instansi Terintegrasi</p>
                </div>
                <div class="pt-6 md:pt-0" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-5xl font-black text-accent mb-2">10<span class="text-white">rb</span></h3>
                    <p class="text-slate-400 font-medium uppercase tracking-wider text-sm">Dokumen LKE Digital</p>
                </div>
                <div class="pt-6 md:pt-0" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-5xl font-black text-white mb-2">100<span class="text-teal-400">%</span></h3>
                    <p class="text-slate-400 font-medium uppercase tracking-wider text-sm">Transparansi Penilaian</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CTA SECTION (PREMIUM WHITE GRADIENT CARD) ================= -->
    <section class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="bg-gradient-to-br from-accent to-teal-700 rounded-3xl p-10 md:p-16 text-center shadow-2xl relative overflow-hidden" data-aos="zoom-in-up">
                <!-- Pola garis abstrak di background CTA -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 2px, transparent 2px); background-size: 30px 30px;"></div>
                
                <h2 class="text-3xl md:text-4xl font-black text-white mb-6 relative z-10">Siap Mengisi Penilaian LKE Instansi Anda?</h2>
                <p class="text-teal-100 mb-10 max-w-2xl mx-auto relative z-10 text-lg">
                    Pastikan Anda telah menerima akun dari administrator. Silakan login ke dalam sistem untuk memulai proses unggah evidence.
                </p>
                <a href="{{ route('login') }}" class="inline-block bg-white text-accent hover:bg-teal-50 px-10 py-4 rounded-full text-lg font-bold shadow-lg transition transform hover:scale-105 relative z-10">
                    Masuk ke Sistem Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-white pt-16 pb-8 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-sm"></i>
                    </div>
                    <span class="font-black text-xl text-gray-900">SI-<span class="text-accent">AKSEL</span></span>
                </div>
                <div class="flex gap-6 text-sm font-medium text-gray-500">
                    <a href="#" class="hover:text-accent transition">Panduan Pengguna</a>
                    <a href="#" class="hover:text-accent transition">Hubungi Bantuan</a>
                    <a href="#" class="hover:text-accent transition">Kebijakan Privasi</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-100 text-center flex flex-col md:flex-row justify-between items-center text-sm text-gray-400 gap-4">
                <p>&copy; 2026 BRIDA Kota Makassar. Hak Cipta Dilindungi.</p>
                <p>Dikembangkan dengan <i class="fas fa-heart text-accent mx-1"></i> untuk Pemerintahan yang Lebih Baik.</p>
            </div>
        </div>
    </footer>

    <!-- Script Inisialisasi AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 50,
        });
    </script>
</body>
</html>