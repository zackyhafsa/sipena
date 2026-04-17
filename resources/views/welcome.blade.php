<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPENA - Sistem Informasi Penilaian Elektronik dan Nilai Akademik</title>

    <!-- PWA / Meta -->
    <meta name="description" content="SIPENA adalah portal ujian berbasis komputer jarak jauh yang handal dan terintegrasi untuk institusi pendidikan.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    <!-- Header Navigation -->
    <header class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="font-extrabold text-2xl tracking-tight text-gray-900">SIPENA</span>
                </div>
                
                <nav class="hidden md:flex gap-8">
                    <a href="#fitur" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Fitur Unggulan</a>
                    <a href="#tentang" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Tentang Kami</a>
                    <a href="#dukungan" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Pusat Bantuan</a>
                </nav>

                <div class="flex items-center gap-4">
                    @auth
                        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                            <a href="{{ url('/admin') }}" class="inline-flex justify-center items-center py-2.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-all transform active:scale-95">Panel Admin</a>
                        @else
                            <a href="{{ route('student.dashboard') }}" class="inline-flex justify-center items-center py-2.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-all transform active:scale-95">Menuju Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex justify-center items-center py-2.5 px-6 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all transform active:scale-95">Masuk Siswa</a>
                        <a href="{{ url('/admin') }}" class="hidden sm:inline-flex justify-center items-center py-2.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-all transform active:scale-95">Portal Guru</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-pattern pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden relative">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-indigo-50/50 to-transparent pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 font-medium text-sm mb-8 animate-fade-in-up">
                <span class="flex w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                Ujian Berbasis Komputer Masa Depan
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-tight animate-fade-in-up" style="animation-delay: 0.1s;">
                Modernisasikan Penilaian <br class="hidden md:block">
                dengan <span class="gradient-text">SIPENA</span>
            </h1>
            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
                Sistem Informasi Penilaian Elektronik dan Nilai Akademik. Mengawasi, menguji, dan melaporkan kemajuan siswa dengan keamanan tinggi, anti-kecurangan, dan terintegrasi penuh.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                @auth
                    <a href="{{ in_array(auth()->user()->role, ['admin', 'superadmin']) ? url('/admin') : route('student.dashboard') }}" class="px-8 py-4 text-lg font-bold rounded-2xl shadow-lg shadow-indigo-600/30 text-white bg-indigo-600 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                        Lanjut ke Sistem &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-4 text-lg font-bold rounded-2xl shadow-lg shadow-indigo-600/30 text-white bg-indigo-600 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                        Mulai Ujian Sekarang
                    </a>
                    <a href="#fitur" class="px-8 py-4 text-lg font-bold rounded-2xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:-translate-y-1 transition-all">
                        Pelajari Fitur
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Mengapa Memilih SIPENA?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Kami merancang arsitektur aplikasi berbasis awan yang ringan, adaptif namun kaya dengan fitur proteksi esensial secara menyeluruh.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Card 1 -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-50 transition-all group">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Keamanan Anti-Kecurangan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Mendeteksi apabila siswa berpindah tab atau meminimize peramban komputer mereka. Sistem akan memberi sanksi dan menutup ujian secara paksa otomatis.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-50 transition-all group">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Penilaian Real-Time</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Nilai akan direkam saat itu juga secara mutlak dan guru dapat memantau hasil, riwayat login, serta mengatur pengkoreksian manual lewat Panel Admin Filament.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-50 transition-all group">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Antarmuka Modern</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Didesain mengutamakan pengalaman user-frendly tanpa loading lama, dibagun di atas stack TailwindCSS dan Livewire 3 agar nyaman diakses komputer & ponsel.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Support CTA Section -->
    <section class="py-20 bg-indigo-600 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-6">Siap Menjadwal Evaluasi Siswa Pertama?</h2>
            <p class="text-indigo-100 text-lg mb-10">Pusat portal untuk siswa telah diaktifkan, dan sistem manajemen pengguna untuk admin telah siap. Hubungi staf IT jika Anda menemukan keluhan teknis.</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="bg-white text-indigo-700 font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all">
                    Masuk ke Sistem
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="md:col-span-2">
                <div class="flex items-center justify-center md:justify-start gap-2 mb-4">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="font-extrabold text-xl text-gray-900">SIPENA</span>
                </div>
                <p class="text-gray-500 text-sm max-w-sm mx-auto md:mx-0">Menyediakan layanan pengelolaan evaluasi pendidikan yang tertata dengan rapi, efisien, serta transparan untuk perkembangan dunia edukasi.</p>
            </div>
            
            <div>
                <h4 class="font-bold text-gray-900 mb-4">Menu Pintas</h4>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li><a href="{{ route('login') }}" class="hover:text-indigo-600 transition-colors">Portal Siswa</a></li>
                    <li><a href="{{ url('/admin') }}" class="hover:text-indigo-600 transition-colors">Portal Admin</a></li>
                    <li><a href="#fitur" class="hover:text-indigo-600 transition-colors">Fitur Aplikasi</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-bold text-gray-900 mb-4">Bantuan Teknis</h4>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-indigo-600 transition-colors">Dokumentasi</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition-colors">Hubungi Proktor</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition-colors">Syarat & Ketentuan Ujian</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-100 pt-8 mt-8 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} SIPENA CBT System. All rights reserved. Platform dibangun menggunakan Laravel & Filament PHP.
        </div>
    </footer>

    <!-- Initial Animation Script -->
    <style>
        .animate-fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>
</html>
