<div class="min-h-screen bg-gray-50/50 py-8 pb-20" x-data="examSecurity()">
    <!-- Fullscreen Overlay -->
    <template x-teleport="body">
        <div x-show="!isFullscreen"
            class="fixed inset-0 z-100 bg-gray-900/95 flex items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full text-center border border-gray-100">
                <div
                    class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 mb-6 border border-red-100">
                    <svg class="h-10 w-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Layar Penuh Diperlukan</h2>
                <p class="text-gray-600 mb-8 leading-relaxed">Anda keluar dari mode layar penuh atau memindahkan tab.
                    Ujian ini mengaktifkan sistem pencegahan kecurangan dan hanya dapat dilanjutkan dalam mode layar
                    penuh.</p>
                <button type="button" @click="enterFullscreen()"
                    class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 transition-all transform active:scale-[0.98]">
                    Masuk Layar Penuh & Lanjutkan
                </button>
            </div>
        </div>
    </template>

    <style>
        .option-radio:checked~div {
            background-color: #eef2ff !important;
            border-color: #6366f1 !important;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.1), 0 2px 4px -1px rgba(99, 102, 241, 0.06) !important;
        }

        .option-radio:checked~div .radio-dot {
            border-color: #6366f1 !important;
            background-color: #6366f1 !important;
            transform: scale(1.1);
        }

        .option-radio:checked~div .radio-svg {
            opacity: 1 !important;
        }

        .option-radio:checked~div .radio-text {
            color: #3730a3 !important;
            font-weight: 600 !important;
        }
    </style>


    <!-- Pengaturan Header / Top Bar -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 lg:sticky lg:top-4 z-10">
        <div
            class="bg-white/90 backdrop-blur-lg p-5 lg:p-6 rounded-2xl shadow-sm border border-gray-200/60 lg:flex lg:justify-between lg:items-center gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 tracking-tight">{{ $exam->title }}</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-gray-100 text-gray-700 text-sm font-medium">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        {{ count($questions) }} Pertanyaan
                    </span>
                    <span class="hidden sm:block text-gray-300">•</span>
                    <span class="text-gray-500 text-sm">Pastikan koneksi internet Anda stabil.</span>
                </div>
            </div>

            <div class="mt-4 lg:mt-0 flex items-center justify-between lg:justify-end gap-4">
                <div
                    class="flex items-center gap-2.5 bg-indigo-50/80 text-indigo-700 px-5 py-2.5 rounded-xl font-bold border border-indigo-100 shadow-sm">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Durasi: {{ $exam->duration }} Menit</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Area Utama Ujian -->
    <form wire:submit.prevent="submit" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">

            <!-- Kolom Kiri: Display Soal -->
            <div class="lg:w-3/4 w-full flex flex-col">
                @foreach ($questions as $index => $question)
                    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-200 min-h-150 flex flex-col"
                        id="question-{{ $index }}" x-show="currentTab === {{ $index }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">

                        <div class="flex items-start gap-4 md:gap-5 mb-8 pb-6 border-b border-gray-100">
                            <span
                                class="shrink-0 w-12 h-12 flex items-center justify-center bg-indigo-50 text-indigo-700 rounded-xl font-bold text-xl border border-indigo-100 shadow-sm">
                                {{ $index + 1 }}
                            </span>
                            <div class="text-gray-800 text-lg md:text-xl font-medium leading-relaxed pt-2">
                                {{ $question->question_text }}
                            </div>
                        </div>

                        @if ($question->image_path)
                            <div class="mt-4 mb-6">
                                <img src="{{ asset('storage/' . $question->image_path) }}" alt="Gambar Soal" class="max-w-full max-h-96 h-auto rounded-lg shadow-sm border border-gray-200">
                            </div>
                        @endif

                        @if ($question->type === 'essay')
                            <div class="mt-4 grow">
                                <textarea wire:model.live.debounce.500ms="answers.{{ $question->id }}" rows="6"
                                    class="w-full p-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-gray-700"
                                    placeholder="Ketik jawaban Anda di sini..."></textarea>
                            </div>
                        @else
                            <div class="space-y-3.5 grow">
                                @php
                                    $optionsList = $this->shuffledOptions[$question->id] ?? [
                                        'option_a',
                                        'option_b',
                                        'option_c',
                                        'option_d',
                                        'option_e',
                                    ];
                                    $displayLetters = ['A', 'B', 'C', 'D', 'E'];
                                    $currentOptIdx = 0;
                                @endphp
                                @foreach ($optionsList as $optionField)
                                    @if ($question->$optionField)
                                        <label class="flex items-start cursor-pointer group relative">
                                            <input type="radio" wire:model="answers.{{ $question->id }}"
                                                name="question_{{ $question->id }}"
                                                value="{{ strtoupper(substr($optionField, -1)) }}" required
                                                class="peer sr-only option-radio">

                                            <div
                                                class="w-full flex items-center p-4 md:p-5 bg-white border-2 border-gray-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50/50 peer-checked:bg-indigo-50 peer-checked:border-indigo-500 transition-all duration-200">
                                                <div class="flex items-center">
                                                    <div
                                                        class="radio-dot w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center shrink-0 transition-all duration-200 mr-4 group-hover:border-indigo-400 peer-checked:border-indigo-500 peer-checked:bg-indigo-500">
                                                        <svg class="radio-svg w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" fill-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <span
                                                    class="radio-text text-gray-700 font-medium leading-relaxed transition-colors duration-200 peer-checked:text-indigo-800 peer-checked:font-bold">
                                                    <span
                                                        class="inline-block w-6 font-bold text-gray-400">{{ $displayLetters[$currentOptIdx++] }}.</span>
                                                    {{ $question->$optionField }}
                                                </span>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- Navigasi Bawah antar soal -->
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-200 gap-4 sm:gap-0">
                    
                    <!-- Indikator Soal (Pindah ke atas pada mobile) -->
                    <div class="text-sm font-medium text-gray-500 sm:order-2"
                        x-text="`Soal ${currentTab + 1} dari {{ count($questions) }}`"></div>

                    <!-- Wrapper Tombol (Kiri Kanan di Mobile) -->
                    <div class="w-full sm:w-auto grid grid-cols-2 sm:flex gap-3 sm:order-1 sm:order-3">
                        <button type="button" :disabled="currentTab === 0"
                            @click="if(currentTab > 0) { currentTab--; window.scrollTo({top: 0, behavior: 'smooth'}) }"
                            :class="currentTab === 0 ? 'opacity-50 cursor-not-allowed bg-gray-50 text-gray-400' :
                                'bg-white hover:bg-gray-50 text-gray-700 border-gray-300 hover:border-gray-400 shadow-sm'"
                            class="flex items-center justify-center gap-1.5 sm:gap-2 px-2 sm:px-6 py-3.5 border font-bold rounded-xl transition-all text-sm sm:text-base">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                            <span class="hidden sm:inline">Soal Sebelumnya</span>
                            <span class="sm:hidden">Sebelumnya</span>
                        </button>

                        <div>
                            <button type="button" x-show="currentTab < {{ count($questions) - 1 }}"
                                @click="currentTab++; window.scrollTo({top: 0, behavior: 'smooth'})"
                                class="w-full flex items-center justify-center gap-1.5 sm:gap-2 px-2 sm:px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-sm hover:shadow transition-all text-sm sm:text-base">
                                <span class="hidden sm:inline">Soal Selanjutnya</span>
                                <span class="sm:hidden">Selanjutnya</span>
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </button>

                            <!-- Tombol selesaikan ujian di navigasi bawah untuk soal terakhir -->
                            <button type="button" x-show="currentTab === {{ count($questions) - 1 }}"
                                @click="$dispatch('open-confirm-modal')"
                                class="w-full flex items-center justify-center gap-1.5 sm:gap-2 px-2 sm:px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition-all text-sm sm:text-base">
                                Selesai
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Peta Navigasi Nomor Soal -->
            <div class="lg:w-1/4 w-full" x-data="{ showConfirmModal: false }"
                @open-confirm-modal.window="showConfirmModal = true">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 sticky top-30">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="p-2 bg-indigo-50 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">Peta Soal</h3>
                    </div>

                    <div class="grid grid-cols-5 md:grid-cols-8 lg:grid-cols-4 xl:grid-cols-5 gap-2.5 mb-8">
                        @foreach ($questions as $index => $question)
                            <button type="button"
                                @click="currentTab = {{ $index }}; window.scrollTo({top: 0, behavior: 'smooth'})"
                                :class="{
                                    'ring-4 ring-indigo-500/30 border-indigo-500 z-10 scale-110': currentTab ===
                                        {{ $index }},
                                    'bg-indigo-600 text-white border-transparent font-bold shadow-sm': $wire.answers[
                                        {{ $question->id }}],
                                    'bg-white text-gray-700 border-gray-200 font-medium hover:border-indigo-300 hover:bg-indigo-50/50':
                                        !$wire.answers[{{ $question->id }}]
                                }"
                                class="w-full aspect-square flex flex-col items-center justify-center rounded-xl border text-sm transition-all transform origin-center relative {{ $question->type === 'essay' ? 'border-b-[3px] border-b-amber-500' : '' }}">
                                {{ $index + 1 }}
                                <!-- Indikator hijau kecil untuk soal yang sudah dijawab jika sedang tidak fokus -->
                                <span
                                    x-show="$wire.answers[{{ $question->id }}] && currentTab !== {{ $index }}"
                                    class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-white"></span>
                            </button>
                        @endforeach
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex flex-col gap-3">
                        <div class="flex items-center gap-3 text-sm text-gray-600 mb-2">
                            <span class="w-3 h-3 rounded-full bg-indigo-600"></span> Sudah Dijawab
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600 mb-2">
                            <span class="w-3 h-3 rounded-full bg-white border-2 border-gray-200"></span> Belum
                            Dijawab
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600 mb-4">
                            <span class="w-5 h-3 rounded-sm border-b-[3px] border-b-amber-500 bg-gray-100"></span> Soal Essai
                        </div>

                        <button type="button" @click="$dispatch('open-confirm-modal')"
                            class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all transform active:scale-[0.98] mt-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Kumpulkan Jawaban
                        </button>

                        <!-- Modal Konfirmasi Selesai Ujian -->
                        <template x-teleport="body">
                            <div x-show="showConfirmModal" style="display: none;"
                                class="fixed inset-0 z-150 overflow-y-auto" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div
                                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="showConfirmModal" x-transition.opacity
                                        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                                        aria-hidden="true"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>

                                    <div x-show="showConfirmModal" x-transition:enter="ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        class="relative z-10 inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-100">
                                        <div class="sm:flex sm:items-start">
                                            <div
                                                class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-12 sm:w-12 border border-emerald-200">
                                                <svg class="h-6 w-6 text-emerald-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                <h3 class="text-xl font-bold text-gray-900 mb-2" id="modal-title">
                                                    Konfirmasi Penyelesaian</h3>
                                                <div class="text-base text-gray-600 space-y-2">
                                                    <p>Apakah Anda yakin ingin mengumpulkan dan menyelesaikan ujian
                                                        ini?
                                                        Pastikan semua jawaban telah terisi.</p>
                                                    <p
                                                        class="font-bold text-emerald-600 bg-emerald-50 p-3 rounded-lg border border-emerald-100 inline-block mt-3 w-full">
                                                        Tindakan ini tidak dapat dibatalkan, nilai akan langsung
                                                        diproses.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-6 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
                                            <button type="button" wire:click="submit"
                                                @click="showConfirmModal = false"
                                                class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 sm:w-auto sm:text-sm transition-all">
                                                Ya, Kumpulkan Ujian
                                            </button>
                                            <button type="button" @click="showConfirmModal = false"
                                                class="mt-3 w-full inline-flex justify-center items-center rounded-xl border-2 border-gray-200 shadow-sm px-4 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-4 focus:ring-gray-100 sm:mt-0 sm:w-auto sm:text-sm transition-all">
                                                Batal, Periksa Lagi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <!-- Modal Peringatan Kecurangan -->
    <template x-teleport="body">
        <div x-show="showWarning" style="display: none;" class="fixed inset-0 z-130 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showWarning" x-transition.opacity
                    class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showWarning" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative z-10 inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-6 text-left shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-2 border-red-500">

                    <div class="flex flex-col items-center text-center">
                        <div
                            class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-5 border-4 border-white shadow-sm -mt-12 relative z-10">
                            <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <h3 class="text-2xl font-black text-gray-900 mb-2" id="modal-title">Peringatan Keamanan!
                        </h3>

                        <div class="mt-2 px-2">
                            <p class="text-base text-gray-600 mb-4">Anda terdeteksi melanggar aturan dengan keluar
                                dari
                                layar penuh atau berpindah aplikasi/tab.</p>

                            <div class="bg-red-50 rounded-xl p-4 border border-red-100 mb-4 inline-block w-full">
                                <p class="text-sm font-bold text-red-800">
                                    Teguran ke: <span class="text-xl" x-text="$wire.violationCount"></span> <span
                                        class="text-red-400 font-medium">dari</span> <span
                                        x-text="$wire.maxViolations"></span>
                                </p>
                            </div>

                            <p class="text-sm text-gray-500 font-medium">Jika batas pelanggaran terlampaui, ujian
                                akan
                                diakhiri secara paksa dengan nilai saat ini.</p>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-8">
                        <button type="button"
                            @click="showWarning = false; isProcessingViolation = false; enterFullscreen();"
                            class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-4 py-3.5 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/40 transition-all transform active:scale-[0.98]">
                            Saya Mengerti & Tidak Akan Mengulangi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Security Check Script -->
    @script
        <script>
            Alpine.data('examSecurity', () => ({
                currentTab: 0,
                isFullscreen: false,
                showWarning: false,
                isProcessingViolation: false,
                timeSinceLoad: Date.now(),

                init() {
                    this.isFullscreen = !!document.fullscreenElement;

                    document.addEventListener('fullscreenchange', () => {
                        this.isFullscreen = !!document.fullscreenElement;
                        if (!this.isFullscreen && !this.showWarning) {
                            this.handleViolation();
                        }
                    });

                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden && !this.showWarning) {
                            this.handleViolation();
                        }
                    });

                    window.addEventListener('blur', () => {
                        if (!this.showWarning) {
                            this.handleViolation();
                        }
                    });

                    $wire.on('show-violation-warning', () => {
                        this.showWarning = true;
                    });

                    $wire.on('show-fatal-warning', () => {
                        $wire.forceSubmit();
                    });

                    // Keyboard shortcut navigation (Left/Right arrows)
                    window.addEventListener('keydown', (e) => {
                        if (e.key === 'ArrowRight' && this.currentTab < {{ count($questions) - 1 }}) {
                            this.currentTab++;
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        }
                        if (e.key === 'ArrowLeft' && this.currentTab > 0) {
                            this.currentTab--;
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        }
                    });
                },

                enterFullscreen() {
                    let elem = document.documentElement;
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen().then(() => {
                            this.isFullscreen = true;
                        }).catch(err => {
                            console.error("Fullscreen error", err);
                        });
                    }
                },

                async handleViolation() {
                    if (this.isProcessingViolation || Date.now() - this.timeSinceLoad < 2000) return;
                    this.isProcessingViolation = true;
                    await $wire.registerViolation();
                }
            }));
        </script>
    @endscript
</div>
