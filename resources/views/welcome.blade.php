<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPENA - Sistem Informasi Penilaian Elektronik dan Nilai Akademik</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}">

    <!-- PWA / Meta -->
    <meta name="description"
        content="SIPENA adalah portal ujian berbasis komputer jarak jauh yang handal dan terintegrasi.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Modern Abstract Background Mesh */
        .bg-mesh {
            background-color: #ffffff;
            background-image:
                radial-gradient(at 40% 20%, hsla(213, 100%, 92%, 1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(158, 100%, 92%, 1) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(213, 100%, 95%, 1) 0px, transparent 50%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #2563eb 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Floating Animation */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 10s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="text-gray-800 bg-white">

    <!-- Header Navigation -->
    <header
        class="fixed w-full top-0 z-50 bg-white/70 backdrop-blur-lg border-b border-gray-200/50 transition-all duration-300"
        x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo/logo.png') }}"
                        class="w-10 h-10 object-contain drop-shadow-md hover:scale-105 transition-transform"
                        alt="SIPENA">
                    <span class="font-black text-2xl tracking-tight text-gray-900">SIPENA</span>
                </div>

                <nav class="hidden md:flex items-center gap-8">
                    <a href="#fitur"
                        class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors">Fitur
                        Unggulan</a>
                    <a href="#cara-kerja"
                        class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors">Cara
                        Kerja</a>
                    <a href="#testimonials"
                        class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors">Testimoni</a>

                </nav>

                <div class="flex items-center gap-4">
                    @auth
                        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                            <a href="{{ url('/admin') }}"
                                class="px-6 py-2.5 rounded-full text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/30 transition-all">Panel
                                Admin</a>
                        @else
                            <a href="{{ route('student.dashboard') }}"
                                class="px-6 py-2.5 rounded-full text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-500/30 transition-all border border-emerald-400">Dashboard
                                Siswa</a>
                        @endif
                    @else
                        <a href="{{ url('/admin') }}"
                            class="hidden sm:inline-flex px-6 py-2 rounded-full text-sm font-bold text-gray-700 bg-transparent hover:bg-gray-100 transition-all border border-gray-200">Portal
                            Guru</a>
                        <a href="{{ route('login') }}"
                            class="px-6 py-2.5 rounded-full text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-emerald-500 hover:from-blue-700 hover:to-emerald-600 hover:shadow-lg hover:shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">Masuk
                            Siswa</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section with Modern Mesh Background -->
    <section class="bg-mesh relative pt-32 pb-20 items-center flex min-h-[90vh] overflow-hidden">
        <!-- Decorative Anime Blobs -->
        <div class="blob bg-blue-400/40 w-96 h-96 rounded-full top-20 -left-20"></div>
        <div class="blob bg-emerald-300/40 w-80 h-80 rounded-full bottom-10 right-10" style="animation-delay: -5s;">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">

                <!-- Hero Text Content -->
                <div class="text-center lg:text-left pt-10">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-blue-200/50 bg-blue-50/50 backdrop-blur-sm text-blue-700 font-semibold text-sm mb-6 animate-fade-in-up">
                        <span class="flex w-2.5 h-2.5 rounded-full bg-emerald-500 relative">
                            <span
                                class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75 animate-ping"></span>
                        </span>
                        Era Baru Penilaian Digital
                    </div>

                    <h1 class="text-4xl sm:text-6xl lg:text-6xl font-black tracking-tight mb-6 leading-[1.15] animate-fade-in-up"
                        style="animation-delay: 0.1s;">
                        Tingkatkan <br class="hidden lg:block" /> Mutu Evaluasi <br />
                        <span class="gradient-text">Sekolah Anda</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-gray-600 mb-10 max-w-2xl mx-auto lg:mx-0 font-medium leading-relaxed animate-fade-in-up"
                        style="animation-delay: 0.2s;">
                        SIPENA dirancang untuk mengawasi, menguji, dan melaporkan instrumen akademik secara terpusat
                        dengan kemampuan pelindung anti-kecurangan tercanggih.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 animate-fade-in-up"
                        style="animation-delay: 0.3s;">
                        @auth
                            <a href="{{ in_array(auth()->user()->role, ['admin', 'superadmin']) ? url('/admin') : route('student.dashboard') }}"
                                class="px-8 py-4 text-base font-bold rounded-full shadow-xl shadow-blue-600/20 text-white bg-blue-600 hover:bg-blue-700 hover:-translate-y-1 transition-all">Sistem
                                Active &rarr;</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-8 py-4 text-base font-bold rounded-full shadow-xl shadow-blue-500/20 text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                                Mulai Coba Ujian
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                            <a href="#fitur"
                                class="px-8 py-4 text-base font-bold rounded-full glass-card text-gray-800 hover:bg-white hover:-translate-y-1 transition-all">
                                Pelajari Dulu Keunggulannya
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Graphic/Mockup Composition Column -->
                <div class="relative w-full h-[450px] lg:h-[600px] hidden md:flex items-center justify-center animate-fade-in-up"
                    style="animation-delay: 0.4s;">
                    <!-- Virtual App Card -->
                    <div class="relative w-full max-w-md">
                        <div
                            class="bg-white rounded-[2rem] shadow-2xl shadow-blue-900/10 p-8 border border-white z-20 relative transform transition-transform hover:-translate-y-2 duration-500 glass-card">
                            <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                                <img src="{{ asset('logo/logo.png') }}" class="w-12 h-12 drop-shadow-sm">
                                <div
                                    class="bg-emerald-100/50 text-emerald-600 text-xs font-bold px-3 py-1 rounded-md border border-emerald-200">
                                    Siswa Aktif: 204</div>
                            </div>

                            <!-- Fake UI Dashboard Elements -->
                            <div class="space-y-4">
                                <div class="w-3/4 h-4 bg-gray-200 rounded-full"></div>
                                <div class="w-1/2 h-3 bg-blue-100 rounded-full mb-6"></div>

                                <div class="grid grid-cols-2 gap-4 mt-8">
                                    <div
                                        class="h-20 bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl flex flex-col justify-center px-4">
                                        <span class="w-6 h-6 bg-blue-200 rounded-full mb-2"></span>
                                        <div class="w-1/2 h-2 bg-blue-300 rounded"></div>
                                    </div>
                                    <div
                                        class="h-20 bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-2xl flex flex-col justify-center px-4">
                                        <span class="w-6 h-6 bg-emerald-200 rounded-full mb-2"></span>
                                        <div class="w-1/2 h-2 bg-emerald-300 rounded"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <div
                                    class="w-24 h-10 bg-blue-600 hover:bg-blue-700 transition rounded-xl shadow-lg shadow-blue-500/30">
                                </div>
                            </div>
                        </div>

                        <!-- Floating Stat Overlay 1 -->
                        <div
                            class="absolute -right-10 top-1/2 bg-white/90 backdrop-blur p-4 rounded-2xl shadow-xl shadow-gray-200/50 border border-white z-30 animate-pulse">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="pr-2">
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Terkoreksi
                                    </p>
                                    <p class="text-xl font-black text-gray-900 leading-tight">Otomatis</p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Notification Overlay 2 -->
                        <div
                            class="absolute -left-12 -top-10 bg-white/90 backdrop-blur p-4 rounded-2xl shadow-xl shadow-gray-200/50 border border-white z-10 transform -rotate-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold">Ujian Aman</p>
                                    <p class="text-sm font-bold text-gray-900 border-b border-emerald-400">Anti Contek
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Advanced Features Overview -->
    <section id="fitur" class="py-24 bg-gray-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div
                    class="inline-flex items-center justify-center gap-2 text-blue-600 font-bold tracking-widest text-xs mb-3">
                    <img src="{{ asset('logo/logo.png') }}" class="w-4 h-4 drop-shadow"> FITUR UTAMA
                </div>
                <h3 class="text-3xl md:text-5xl font-black text-gray-900 mb-6 tracking-tight">Menjadikan Administrasi
                    Lebih Gampang</h3>
                <p class="text-gray-500 text-lg font-medium leading-relaxed">SIPENA dirancang dengan orientasi
                    kepraktisan untuk mencegah kerumitan teknis bagi pengawas dan memberikan ruang nyaman untuk siswa.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Card Pro 1 -->
                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border md:border-t-4 border-gray-100 md:border-t-blue-500 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-8">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-4">Pengawasan Super Ketat</h4>
                    <p class="text-gray-500 leading-relaxed font-medium">
                        Fitur deteksi otomatis akan merekam aktivitas nakal seperti membuka tab lain, menyontek materi,
                        hingga merekam ketika browser di minimize.
                    </p>
                </div>

                <!-- Card Pro 2 -->
                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border md:border-t-4 border-gray-100 md:border-t-emerald-500 hover:shadow-xl transition-all duration-300">
                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-8">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-4">Koreksi Hitungan Detik</h4>
                    <p class="text-gray-500 leading-relaxed font-medium">
                        Biarkan mesin yang bekerja untuk menilai opsi ganda. Anda juga tetap bisa memeriksa essai secara
                        manual di dalam panel admin.
                    </p>
                </div>

                <!-- Card Pro 3 -->
                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border md:border-t-4 border-gray-100 md:border-t-gray-400 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-700 flex items-center justify-center mb-8">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-4">Responsive Segala Device</h4>
                    <p class="text-gray-500 leading-relaxed font-medium">
                        Tak peduli device yang dipakai siswa (HP, Tablet, maupun PC Layar Lebar), tampilan soal akan
                        tersesuaikan ukurannya secara dinamis.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section id="cara-kerja" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div
                    class="inline-flex items-center justify-center gap-2 text-emerald-600 font-bold tracking-widest text-xs mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg> CARA KERJA
                </div>
                <h3 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 tracking-tight">Mudah Digunakan dalam 3
                    Langkah</h3>
                <p class="text-gray-500 text-lg font-medium">Proses yang ringkas untuk memulai dan menyelesaikan
                    evaluasi sekolah Anda tanpa membuang waktu.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-10 relative">
                <!-- Connector Line -->
                <div class="hidden md:block absolute top-12 left-1/6 right-1/6 h-0.5 bg-gray-100 z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 text-center">
                    <div
                        class="w-24 h-24 mx-auto bg-blue-50 rounded-full flex items-center justify-center border-8 border-white shadow-sm mb-6">
                        <span class="text-3xl font-black text-blue-600">1</span>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Persiapan Ujian</h4>
                    <p class="text-gray-500 font-medium">Administrator/Guru mengunggah bank soal, menentukan jadwal, dan
                        membagikan token ujian ke siswa.</p>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 text-center">
                    <div
                        class="w-24 h-24 mx-auto bg-emerald-50 rounded-full flex items-center justify-center border-8 border-white shadow-sm mb-6">
                        <span class="text-3xl font-black text-emerald-600">2</span>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Pelaksanaan Ujian</h4>
                    <p class="text-gray-500 font-medium">Siswa masuk ke portal, memasukkan token, lalu mengerjakan butir
                        soal dengan sistem keamanan ketat.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 text-center">
                    <div
                        class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center border-8 border-white shadow-sm mb-6">
                        <span class="text-3xl font-black text-gray-700">3</span>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Rekapitulasi Instan</h4>
                    <p class="text-gray-500 font-medium">Sistem mengkalkulasi skoring seketika. Guru dapat mengunduh
                        nilai dalam format Excel tanpa repot mengoreksi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h3 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 tracking-tight">Apa Kata Mereka?</h3>
                <p class="text-gray-500 text-lg font-medium">Pengalaman langsung dari tenaga pendidik yang telah
                    menggunakan SIPENA.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative">
                    <div class="text-blue-500 mb-4">
                        <svg class="w-10 h-10 opacity-30" fill="currentColor" viewBox="0 0 32 32">
                            <path
                                d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H6.3c1.3-2.3 3.7-3.8 6.5-3.8h1.2V8H10zm16 0c-3.3 0-6 2.7-6 6v10h10V14h-7.7c1.3-2.3 3.7-3.8 6.5-3.8h1.2V8H26z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 italic mb-6 font-medium leading-relaxed">"Proses koreksi ujian yang biasanya
                        memakan waktu berhari-hari kini selesai dalam hitungan detik. Import soal dari Excel sangat
                        membantu!"</p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                            AW</div>
                        <div>
                            <h5 class="font-bold text-gray-900">Ahmad Wahyudi</h5>
                            <span class="text-sm text-gray-500">Guru Matematika</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative">
                    <div class="text-emerald-500 mb-4">
                        <svg class="w-10 h-10 opacity-30" fill="currentColor" viewBox="0 0 32 32">
                            <path
                                d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H6.3c1.3-2.3 3.7-3.8 6.5-3.8h1.2V8H10zm16 0c-3.3 0-6 2.7-6 6v10h10V14h-7.7c1.3-2.3 3.7-3.8 6.5-3.8h1.2V8H26z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 italic mb-6 font-medium leading-relaxed">"Sistem keamanan di portal siswa
                        benar-benar efektif mengurangi kecurangan. Tampilan responsif muid nyaman pakai HP."</p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center font-bold text-emerald-600">
                            SN</div>
                        <div>
                            <h5 class="font-bold text-gray-900">Siti Nurhaliza</h5>
                            <span class="text-sm text-gray-500">Wakasek Kurikulum</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Support CTA Full Width Base Banner -->
    <section id="solusi"
        class="py-28 relative overflow-hidden bg-gray-900 flex justify-center border-t-8 border-blue-600">
        <!-- Decoration Inside Black Box -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-bl from-blue-900/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 w-1/2 h-full bg-gradient-to-tr from-emerald-900/20 to-transparent">
            </div>
        </div>

        <div class="relative z-10 max-w-4xl px-4 flex flex-col items-center text-center">
            <div class="p-3 bg-white/10 rounded-2xl mb-8 backdrop-blur-sm border border-white/5">
                <img src="{{ asset('logo/logo.png') }}" class="w-16 h-16 drop-shadow-xl brightness-0 invert" alt="">
            </div>
            <h2 class="text-4xl md:text-5xl font-black mb-6 text-white tracking-tight">Siap Memulai Evaluasi Akademik
                Sekarang?</h2>
            <p class="text-gray-400 text-lg md:text-xl mb-12 font-medium max-w-2xl">Platform ini dibuat eksklusif untuk
                kemudahan pengawas dalam membuat bank soal sembari melatih integritas murid.</p>

            <div class="flex gap-4">
                <a href="{{ route('login') }}"
                    class="px-8 py-4 rounded-full font-bold text-blue-900 bg-white hover:bg-gray-100 hover:scale-105 shadow-[0_0_40px_rgba(255,255,255,0.1)] transition-all flex items-center gap-2">
                    Akses Portal Siswa
                </a>
                <a href="{{ url('/admin') }}"
                    class="hidden sm:inline-flex px-8 py-4 rounded-full font-bold text-white border-2 border-gray-700 bg-transparent hover:border-gray-500 transition-all flex items-center gap-2">
                    Masuk Admin
                </a>
            </div>
        </div>
    </section>

    <!-- Footer Super Minimalist -->
    <footer class="bg-white py-10">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm flex flex-col md:flex-row justify-between items-center gap-6 font-medium text-gray-500">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo/logo.png') }}" class="w-6 h-6 object-contain grayscale opacity-60">
                <span class="font-bold text-gray-400">&copy; {{ date('Y') }} SIPENA CBT - Hak Cipta Dilindungi.</span>
            </div>

            <div class="flex gap-6">
                <a href="#" class="hover:text-blue-600 transition-colors">Term of Service</a>
                <a href="#" class="hover:text-blue-600 transition-colors">Bantuan Proktor</a>
                <a href="#" class="hover:text-blue-600 transition-colors">Dokumentasi</a>
            </div>
        </div>
    </footer>
</body>

</html>