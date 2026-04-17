<div class="space-y-8 animate-fade-in-up">
    <!-- Welcome Header -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Selamat Datang, {{ $user->name }}! 👋</h1>
            <p class="text-gray-600">Pilih ujian yang tersedia di bawah ini untuk memulai. Pastikan koneksi internet stabil sebelum menjadwal ujian.</p>
        </div>
        <div class="hidden sm:flex p-4 bg-indigo-50 rounded-full">
            <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
        </div>
    </div>

    <!-- Exam List Section -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Daftar Ujian Aktif
        </h2>

        @if($exams->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada ujian aktif</h3>
                <p class="text-gray-500">Saat ini tidak ada ujian yang dijadwalkan untuk Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($exams as $exam)
                    @php
                        $isCompleted = isset($completedExams[$exam->id]);
                    @endphp

                    <div class="bg-white rounded-2xl border transition-all duration-300 relative overflow-hidden flex flex-col
                        {{ $isCompleted ? 'border-gray-200 opacity-75' : 'border-indigo-100 shadow-sm hover:shadow-md hover:-translate-y-1' }}">
                        
                        <!-- Status Badge Top Right -->
                        @if($isCompleted)
                            <div class="absolute top-4 right-4 bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                SELESAI
                            </div>
                        @else
                            <div class="absolute top-4 right-4 bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                AKTIF
                            </div>
                        @endif

                        <div class="p-6 flex-grow">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 truncate pr-16" title="{{ $exam->title }}">{{ $exam->title }}</h3>
                            <p class="text-gray-500 text-sm mb-6 line-clamp-2 min-h-[40px]">{{ $exam->description ?? 'Tidak ada deskripsi' }}</p>

                            <div class="space-y-3">
                                <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-medium text-gray-800">{{ $exam->duration }} Menit</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-medium text-gray-800">{{ $exam->questions()->count() }} Soal</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0 mt-auto">
                            @if($isCompleted)
                                <button disabled class="w-full bg-gray-100 text-gray-400 font-medium py-3 px-4 rounded-xl cursor-not-allowed border border-gray-200">
                                    Ujian Selesai
                                </button>
                            @else
                                <a href="{{ route('student.exam', $exam->id) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-xl transition-colors shadow-sm focus:ring-4 focus:ring-indigo-100 focus:outline-none">
                                    Mulai Ujian
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
