<div class="space-y-8 animate-fade-in-up max-w-7xl mx-auto">
    <!-- Welcome Header / Mobile Friendly Modern Approach -->
    <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-6 sm:p-10 shadow-xl overflow-hidden border border-blue-500">
        <!-- Floating Decorative Blobs -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-24 -left-10 w-48 h-48 bg-emerald-400/20 rounded-full blur-2xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="text-white">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider mb-4 border border-white/30">
                    <span class="relative flex w-2 h-2">
                      <span class="animate-ping" style="opacity: 0.75; position: absolute; height: 100%; width: 100%; border-radius: 9999px; background-color: #6ee7b7;"></span>
                      <span class="relative inline-flex rounded-full w-2 h-2 bg-emerald-400"></span>
                    </span>
                    Portal Aktif
                </div>
                <h1 class="text-3xl sm:text-4xl font-black mb-2 tracking-tight">Selamat Datang, {{ explode(' ', $user->name)[0] }}! 👋</h1>
                <p class="text-blue-100 text-sm sm:text-base max-w-xl leading-relaxed">
                    Pilih ujian yang tersedia di bawah ini untuk memulai. Pastikan koneksi internet Anda stabil dan siapkan diri sebelum membuka soal.
                </p>
            </div>
            
            <!-- Animated Icon Banner Right -->
            <div class="hidden md:flex p-5 bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-inner rotate-3 hover:rotate-0 transition-all duration-300">
                <svg class="w-14 h-14 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Exam List Section -->
    <div class="mt-10">
        <div class="flex items-center justify-between mb-6 border-b border-gray-200/60 pb-4">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <div class="p-2 bg-blue-100/50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                Daftar Ujian Aktif
            </h2>
            <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">{{ $exams->count() }} Jadwal</span>
        </div>

        @if($exams->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-200 shadow-sm flex flex-col items-center justify-center py-20 min-h-[400px]">
                <div class="inline-flex items-center justify-center p-6 rounded-full bg-gray-50 mb-6 drop-shadow-sm">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2 tracking-tight">Belum Ada Ujian Aktif</h3>
                <p class="text-gray-500 font-medium max-w-md">Saat ini tidak ada daftar ujian yang dijadwalkan untuk kelas Anda. Silakan istirahat atau hubungi pengawas.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($exams as $exam)
                    @php
                        $isCompleted = isset($completedExams[$exam->id]);
                    @endphp

                    <div class="bg-white rounded-3xl border transition-all duration-300 relative flex flex-col bg-clip-padding {{ $isCompleted ? 'border-gray-200 opacity-90' : 'border-blue-100 shadow-[0_4px_20px_-4px_rgba(37,99,235,0.1)] hover:shadow-[0_10px_25px_-5px_rgba(37,99,235,0.15)] hover:-translate-y-1.5 hover:border-blue-300' }}">                                        
                        
                        <!-- Header Status Bar -->
                        <div class="px-6 pt-6 pb-4 flex justify-between items-start border-b border-gray-50">
                            <div class="bg-gray-100/80 p-2 rounded-xl">
                                <svg class="w-8 h-8 {{ $isCompleted ? 'text-gray-400' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            
                            @if($isCompleted)
                                <div class="bg-emerald-50 text-emerald-600 text-[11px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-sm border border-emerald-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> SELESAI
                                </div>
                            @else
                                <div class="bg-blue-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-md shadow-blue-500/20">
                                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> TERSEDIA
                                </div>
                            @endif
                        </div>

                        <!-- Card Content -->
                        <div class="px-6 py-5 flex-grow">
                            <h3 class="text-xl font-black text-gray-900 mb-2 leading-snug line-clamp-2" title="{{ $exam->title }}">{{ $exam->title }}</h3>
                            <p class="text-gray-500 text-sm mb-6 line-clamp-2 min-h-[40px] font-medium leading-relaxed">{{ $exam->description ?? 'Tidak ada petunjuk tambahan' }}</p>
                            
                            <!-- Exam Details Mini-grid -->
                            <div class="grid grid-cols-2 gap-3 mb-2">
                                <div class="flex items-center text-sm font-semibold text-gray-700 bg-gray-50/80 hover:bg-gray-100 p-2.5 rounded-xl transition-colors border border-gray-100">
                                    <svg class="w-5 h-5 mr-2.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $exam->duration }} Menit
                                </div>
                                <div class="flex items-center text-sm font-semibold text-gray-700 bg-gray-50/80 hover:bg-gray-100 p-2.5 rounded-xl transition-colors border border-gray-100">
                                    <svg class="w-5 h-5 mr-2.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $exam->questions()->count() }} Soal
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Base -->
                        <div class="px-6 pb-6 pt-2 mt-auto">
                            @if($isCompleted)
                                @php $result = $completedExams[$exam->id]; @endphp
                                @if($exam->show_result_on_completion)
                                    <div class="bg-gray-50/50 border border-gray-200 rounded-2xl p-4 text-center mt-2 shadow-inner">
                                        <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Ringkasan Skor</h4>
                                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                            <!-- Pilihan Ganda -->
                                            <div class="bg-white py-2 px-1 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-center items-center">
                                                <span class="block text-gray-400 text-xs font-bold uppercase mb-0.5">PG</span>
                                                <span class="font-black text-xl text-gray-800">{{ $result->score_pg ?? 0 }}</span>
                                            </div>
                                            <!-- Essai -->
                                            <div class="bg-white py-2 px-1 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-center items-center">
                                                <span class="block text-gray-400 text-xs font-bold uppercase mb-0.5">Esai</span>
                                                @if($result->is_scored_manually && $result->score_essay === null)
                                                    <span class="font-bold text-amber-500 text-xs py-1">Proses</span>
                                                @else
                                                    <span class="font-black text-xl text-gray-800">{{ $result->score_essay ?? 0 }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Total -->
                                        <div class="bg-gradient-to-tr from-blue-50 to-emerald-50 border border-emerald-100 p-3 rounded-xl shadow-sm">
                                            <span class="block text-blue-600/70 text-[10px] font-black uppercase tracking-widest mb-1.5">Nilai Total</span>
                                            @if($result->is_scored_manually && $result->score === null)
                                                <span class="text-xs font-black text-amber-600 uppercase tracking-widest">Tertunda</span>
                                            @else
                                                <span class="text-3xl font-black text-emerald-600 drop-shadow-sm">{{ $result->score ?? $result->score_pg ?? 0 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <!-- Finished but score hidden -->
                                    <div class="bg-gray-100 text-gray-500 font-bold py-4 px-4 rounded-2xl border border-gray-200 text-center flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Telah Dikerjakan
                                    </div>
                                @endif
                            @else
                                <button type="button" wire:click="openTokenModal({{ $exam->id }})"
                                    class="w-full relative flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl transition-all shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] transform hover:scale-[1.02] focus:ring-4 focus:ring-blue-200 outline-none overflow-hidden">
                                    <span class="relative z-10 flex items-center gap-2 text-base tracking-wide">
                                        Mulai Kerjakan
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Token Verification Modal (Modern UI) --}}
    <div x-data="{ show: @entangle('showTokenModal') }"
         x-show="show"
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="token-modal-title" role="dialog" aria-modal="true"
         x-cloak>
        <div class="flex flex-col items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Glassmorphism Backdrop -->
            <div x-show="show"
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity"
                 wire:click="closeTokenModal"></div>

            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                    <div class="sm:flex sm:items-start flex-col items-center text-center">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 border-4 border-white shadow-sm mb-5 relative">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-20 animate-ping"></span>
                            <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-2xl leading-6 font-black text-gray-900 mb-2 tracking-tight text-center" id="token-modal-title">Masukkan Token</h3>
                            <p class="text-sm font-medium text-gray-500 mb-6 text-center">Mintalah token jadwal dari pengawas/guru Anda sebelum ujian dimulai!</p>
                            
                            <div class="mt-2">
                                <input type="text" wire:model="inputToken"
                                    wire:keydown.enter="verifyToken"
                                    class="w-full text-center text-2xl font-black tracking-[0.5em] uppercase px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-gray-300 placeholder:tracking-normal placeholder:font-medium placeholder:text-base outline-none"
                                    placeholder="Ketik token di sini"
                                    maxlength="15"
                                    autocomplete="off"
                                    autofresh>
                                @error('inputToken')
                                    <p class="mt-3 text-sm text-red-500 font-bold bg-red-50 p-2 rounded-lg border border-red-100 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Actions Base -->
                <div class="bg-gray-50 border-t border-gray-100 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                    <button type="button" wire:click="verifyToken"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3.5 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-500/30 sm:w-auto sm:text-sm transition-all transform hover:-translate-y-0.5 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="verifyToken" class="flex items-center gap-2">
                            Verifikasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </span>
                        <span wire:loading wire:target="verifyToken" class="flex items-center gap-2 text-blue-100">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Memeriksa...
                        </span>
                    </button>
                    <button type="button" wire:click="closeTokenModal" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-3.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-100 sm:mt-0 sm:w-auto sm:text-sm transition-all">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px) scale(0.98); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>