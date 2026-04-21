const fs = require('fs');
let content = fs.readFileSync('resources/views/livewire/student-exam.blade.php', 'utf8');

// Replace the main form layout with a 2-column grid layout for pagination
let oldFormStart = /<form wire:submit\.prevent="submit" class="space-y-8">[\s\S]*?@foreach\(\$questions as \$index => \$question\)/;
let newFormStart = `<form wire:submit.prevent="submit">
        <div class="flex flex-col lg:flex-row gap-6">
            
            <!-- Left Column: Soal Ujian -->
            <div class="lg:w-3/4 w-full">
                @foreach($questions as $index => $question)`;
content = content.replace(oldFormStart, newFormStart);

let oldQuestionDiv = /<div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 scroll-mt-28"\s*id="question-\{\{ \$index \}\}">/;
let newQuestionDiv = `<div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 min-h-[400px]" 
                    id="question-{{ $index }}"
                    x-show="currentTab === {{ $index }}"
                    x-transition.opacity.duration.300ms>`;
content = content.replace(oldQuestionDiv, newQuestionDiv);

let oldFormEnd = /@endforeach\s*<div class="pt-8" x-data="\{ showConfirmModal: false \}"/;
let newFormEnd = `@endforeach
                
                <!-- Navigasi Bawah -->
                <div class="mt-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <button type="button" 
                        x-show="currentTab > 0" 
                        @click="currentTab--; window.scrollTo({top: 0, behavior: 'smooth'})"
                        class="flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Soal Sebelumnya
                    </button>
                    <div x-show="currentTab === 0"></div> <!-- Placeholder untuk flex space-between -->
                    
                    <button type="button" 
                        x-show="currentTab < {{ count($questions) - 1 }}" 
                        @click="currentTab++; window.scrollTo({top: 0, behavior: 'smooth'})"
                        class="flex items-center gap-2 px-6 py-3 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold rounded-xl transition-colors">
                        Soal Selanjutnya
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Right Column: Navigasi Nomor Soal -->
            <div class="lg:w-1/4 w-full" x-data="{ showConfirmModal: false }" @open-confirm-modal.window="showConfirmModal = true">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-[4.5rem]">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Navigasi Soal
                    </h3>
                    
                    <div class="grid grid-cols-5 gap-2 mb-6">
                        @foreach($questions as $index => $question)
                            @php
                                $isAnswered = isset($answers[$question->id]);
                            @endphp
                            <button type="button" 
                                @click="currentTab = {{ $index }}; window.scrollTo({top: 0, behavior: 'smooth'})"
                                :class="[
                                    currentTab === {{ $index }} ? 'ring-2 ring-offset-1 ring-indigo-500' : '',
                                    $wire.answers[{{$question->id}}] ? 'bg-indigo-600 text-white border-transparent' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
                                ]"
                                class="w-10 h-10 flex items-center justify-center rounded-lg border font-semibold text-sm transition-all">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>

                    `;
content = content.replace(oldFormEnd, newFormEnd);

let closeColumns = `                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        </div>
    </form>`;

content = content.replace(/<\/template>\s*<\/div>\s*<\/form>/, closeColumns);


let newScript = `@script
    <script>
        Alpine.data('examSecurity', () => ({
            isFullscreen: false,
            showWarning: false,
            isProcessingViolation: false,
            timeSinceLoad: Date.now(),
            currentTab: 0,
            
            init() {`;

content = content.replace(/@script\s*<script>\s*Alpine\.data\('examSecurity', \(\) => \(\{/, newScript);

fs.writeFileSync('resources/views/livewire/student-exam.blade.php', content);
