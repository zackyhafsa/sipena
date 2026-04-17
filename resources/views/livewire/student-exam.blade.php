<div class="max-w-4xl mx-auto pb-20">
    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 mb-8 sticky top-[4.5rem] z-40"
        style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $exam->title }}</h1>
                <p class="text-gray-500 mt-1">{{ count($questions) }} Pertanyaan</p>
            </div>
            <div class="flex items-center gap-2 bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Durasi: {{ $exam->duration }} Menit</span>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="submit" class="space-y-8">
        @foreach($questions as $index => $question)
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 scroll-mt-28"
                id="question-{{ $index }}">
                <div class="flex items-start gap-4 mb-6">
                    <span
                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-indigo-100 text-indigo-700 rounded-full font-bold text-sm">
                        {{ $index + 1 }}
                    </span>
                    <div class="text-gray-800 text-lg md:text-xl font-medium leading-relaxed pt-1">
                        {{ $question->question_text }}
                    </div>
                </div>

                <div class="space-y-3 pl-12">
                    @foreach(['option_a', 'option_b', 'option_c', 'option_d', 'option_e'] as $optionField)
                        @if($question->$optionField)
                            <label class="flex items-start cursor-pointer group relative">
                                <input type="radio" wire:model="answers.{{ $question->id }}"
                                    value="{{ strtoupper(substr($optionField, -1)) }}" required class="peer sr-only">

                                <div
                                    class="w-full flex p-4 bg-gray-50 border-2 border-transparent rounded-xl peer-checked:bg-indigo-50 peer-checked:border-indigo-500 hover:bg-gray-100 transition-all duration-200">
                                    <div class="flex items-center h-6">
                                        <div
                                            class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-500 flex items-center justify-center flex-shrink-0 transition-colors mr-4 group-hover:border-gray-400">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" fill-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="text-gray-700 peer-checked:text-indigo-900 font-medium leading-relaxed">
                                        {{ strtoupper(substr($optionField, -1)) }}. {{ $question->$optionField }}
                                    </span>
                                </div>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="pt-8" x-data="{ showConfirmModal: false }">
            <button type="button" @click="showConfirmModal = true"
                class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-md text-lg font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform active:scale-[0.99]">
                Selesaikan Ujian & Simpan Jawaban
                <svg class="ml-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </button>

            <!-- Modal Konfirmasi Custom -->
            <div x-show="showConfirmModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showConfirmModal" x-transition.opacity
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div x-show="showConfirmModal" x-transition.scale
                        class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Konfirmasi
                                    Penyelesaian</h3>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p>Apakah Anda yakin ingin menyelesaikan ujian ini? Pastikan semua jawaban sudah
                                        terisi dengan benar.</p>
                                    <p class="mt-2 text-indigo-600 font-semibold">Tindakan ini tidak dapat dibatalkan,
                                        dan nilai akan langsung diproses.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Ya,
                                Selesaikan Ujian</button>
                            <button type="button" @click="showConfirmModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Batal,
                                Periksa Lagi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Peringatan Kecurangan -->
    <div x-data="{ showWarning: false, violationCount: 0, maxViolations: 0 }"
        @show-violation-warning.window="showWarning = true; violationCount = $event.detail.count; maxViolations = $event.detail.max"
        x-show="showWarning" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showWarning" x-transition.opacity
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showWarning" x-transition.scale
                class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Peringatan Keamanan
                            Ujian!</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Anda terdeteksi melakukan perpindahan tab/halaman selama
                                ujian berlangsung.</p>
                            <p class="mt-2 text-sm font-bold text-red-600">Pelanggaran: <span
                                    x-text="violationCount"></span> dari <span x-text="maxViolations"></span></p>
                            <p class="mt-2 text-sm text-gray-500">Jika jumlah pelanggaran mencapai batas maksimum, ujian
                                Anda akan <strong class="text-red-600">dihentikan secara otomatis!</strong></p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showWarning = false"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Saya
                        Mengerti & Tidak Mengulangi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Check Script -->
    @script
    <script>
        let isProcessingViolation = false;
        let timeSinceLoad = Date.now();

        const handleViolation = () => {
            if (isProcessingViolation || Date.now() - timeSinceLoad < 3000) return;

            isProcessingViolation = true;
            $wire.registerViolation();

            setTimeout(() => {
                isProcessingViolation = false;
            }, 3000);
        };

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                handleViolation();
            }
        });

        window.addEventListener('blur', () => {
            handleViolation();
        });
    </script>
    @endscript
</div>