const fs = require('fs');
let content = fs.readFileSync('resources/views/livewire/student-exam.blade.php', 'utf8');

content = content.replace('<form wire:submit.prevent="submit" class="space-y-8">', '<form wire:submit.prevent="submit"><div class="flex flex-col lg:flex-row gap-6"><div class="lg:w-3/4 w-full">');

content = content.replace(/<div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 scroll-mt-28"\s*id="question-\{\{ \$index \}\}">/g, 
`<div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 min-h-[400px]" 
                    id="question-{{ $index }}"
                    x-show="currentTab === {{ $index }}"
                    x-transition.opacity.duration.300ms>`);


let endStr = `<div class="pt-8" x-data="{ showConfirmModal: false }" @open-confirm-modal.window="showConfirmModal = true">`;

let newNav = `<!-- Navigasi Bawah -->
                <div class="mt-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <button type="button" 
                        x-show="currentTab > 0" 
                        @click="currentTab--; window.scrollTo({top: 0, behavior: 'smooth'})"
                        class="flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                        Soal Sebelumnya
                    </button>
                    <div x-show="currentTab === 0"></div>
                    
                    <button type="button" 
                        x-show="currentTab < {{ count($questions) - 1 }}" 
                        @click="currentTab++; window.scrollTo({top: 0, behavior: 'smooth'})"
                        class="flex items-center gap-2 px-6 py-3 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold rounded-xl transition-colors">
                        Soal Selanjutnya
                    </button>
                </div>
            </div>

            <!-- Right Column: Navigasi Nomor Soal -->
            <div class="lg:w-1/4 w-full" x-data="{ showConfirmModal: false }" @open-confirm-modal.window="showConfirmModal = true">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-[4.5rem]">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        Navigasi Soal
                    </h3>
                    
                    <div class="grid grid-cols-5 gap-2 mb-6">
                        @foreach($questions as $index => $question)
                            <button type="button" 
                                @click="currentTab = {{ $index }}; window.scrollTo({top: 0, behavior: 'smooth'})"
                                :class="{
                                    'ring-2 ring-offset-1 ring-indigo-500': currentTab === {{ $index }},
                                    'bg-indigo-600 text-white border-transparent': '{{$question->id}}' in $wire.answers,
                                    'bg-white text-gray-600 border-gray-200 hover:bg-gray-50': !('{{$question->id}}' in $wire.answers)
                                }"
                                class="w-10 h-10 flex items-center justify-center rounded-lg border font-semibold text-sm transition-all">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div class="pt-2 border-t border-gray-100">

                        <button type="button" @click="$dispatch('open-confirm-modal')"
                            class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform active:scale-[0.99]">
                            Selesaikan Ujian
                        </button>
                        
                        <!-- Modal Konfirmasi -->
                        <template x-teleport="body">
                            <div x-show="showConfirmModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="showConfirmModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showConfirmModal = false"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    <div x-show="showConfirmModal" x-transition.scale class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 relative z-50">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Konfirmasi Penyelesaian</h3>
                                                <div class="mt-2 text-sm text-gray-500">
                                                    <p>Apakah Anda yakin ingin menyelesaikan ujian ini? Pastikan semua jawaban sudah terisi dengan benar.</p>
                                                    <p class="mt-2 text-indigo-600 font-semibold">Tindakan ini tidak dapat dibatalkan, dan nilai akan langsung diproses.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse relative z-50">
                                            <button type="button" wire:click="submit" @click="showConfirmModal = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Ya, Selesaikan Ujian</button>
                                            <button type="button" @click="showConfirmModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Batal, Periksa Lagi</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
`;

content = content.replace(/<div class="pt-8" x-data="\{ showConfirmModal: false \}" @open-confirm-modal\.window="showConfirmModal = true">[\s\S]*?<\/template>\s*<\/div>/, newNav);

content = content.replace(/<\/form>/, `</div></form>`);

fs.writeFileSync('resources/views/livewire/student-exam.blade.php', content);
