<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8 font-sans text-slate-800 selection:bg-indigo-100 selection:text-indigo-900">
    <div class="max-w-4xl mx-auto">
        @if($isFinished)
            <div class="bg-white shadow-2xl rounded-3xl p-8 sm:p-12 text-center border-t-8 border-green-500 relative overflow-hidden">
                <!-- Decorative Background shapes -->
                <div class="absolute top-0 right-0 -mt-16 -mr-16 w-32 h-32 bg-green-50 rounded-full opacity-50"></div>
                <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-40 h-40 bg-indigo-50 rounded-full opacity-50"></div>
                
                <div class="relative z-10">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-4xl font-extrabold text-slate-800 mb-3 tracking-tight">Ujian Selesai!</h2>
                    <p class="text-slate-500 text-lg mb-8">Kerja bagus! Jawaban Anda telah berhasil disimpan ke dalam sistem.</p>

                    <div class="bg-gradient-to-b from-slate-50 to-white rounded-2xl p-8 mb-8 inline-block w-full max-w-md border border-slate-100 shadow-sm relative">
                        <p class="text-sm text-slate-400 uppercase tracking-widest font-bold mb-2">Nilai Akhir Anda</p>
                        <p class="text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 my-4 drop-shadow-sm">
                            {{ $score }}
                        </p>

                        @if($cheatWarnings > 0)
                            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-center gap-2 text-rose-500 bg-rose-50 px-4 py-3 rounded-xl inline-flex text-sm font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                Tercatat {{ $cheatWarnings }} Peringatan Pelanggaran
                            </div>
                        @endif
                    </div>

                    <div>
                        <a href="/"
                            class="inline-flex items-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-slate-800 focus:ring-4 focus:ring-slate-200 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            Kembali ke Beranda
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

        @else
            <!-- Active Exam Header -->
            <div class="bg-white shadow-sm border border-slate-200 rounded-2xl p-4 sm:p-6 mb-6 flex flex-col sm:flex-row justify-between items-center z-10 relative mt-2">
                <div class="flex items-center gap-4 mb-4 sm:mb-0">
                    <div class="bg-indigo-100 p-3 rounded-xl hidden sm:block">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $exam->title }}</h1>
                        <p class="text-slate-500 font-medium flex items-center gap-2 mt-1">
                            <span class="bg-slate-100 px-2 py-0.5 rounded text-sm text-slate-600">Soal {{ $currentQuestionIndex + 1 }} dari {{ count($questions) }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    @if($cheatWarnings > 0)
                        <div class="flex-1 sm:flex-none bg-rose-50 border border-rose-200 text-rose-600 px-4 py-2.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2 animate-pulse shadow-sm">
                            ⚠️ Peringatan: {{ $cheatWarnings }}/3
                        </div>
                    @endif
                    <div class="flex-1 sm:flex-none bg-slate-900 shadow-md text-white px-5 py-2.5 rounded-xl font-mono font-bold text-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $exam->duration }} Menit
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            @php $progress = (( $currentQuestionIndex ) / count($questions)) * 100; @endphp
            <div class="w-full bg-slate-200 rounded-full h-2 mb-8 shadow-inner overflow-hidden relative">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500 ease-out" style="width: {{ $progress > 0 ? $progress : 2 }}%"></div>
            </div>

            <!-- Question Card -->
            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl overflow-hidden mb-8 border border-slate-100 transition-all">
                @php
                    $currentQuestion = $questions[$currentQuestionIndex];
                @endphp

                <div class="p-6 sm:p-10">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="bg-indigo-50 text-indigo-700 font-black px-5 py-2 rounded-xl text-lg border border-indigo-100 shadow-sm">Soal {{ $currentQuestionIndex + 1 }}</span>
                        <div class="h-px bg-slate-200 flex-1"></div>
                        <span class="text-slate-400 text-sm font-medium">Bobot: {{ $currentQuestion->score_weight ?? 1 }}</span>
                    </div>

                    <div class="prose prose-lg max-w-none mb-10 text-slate-800 leading-relaxed font-medium">
                        {!! nl2br(e($currentQuestion->question_text)) !!}
                    </div>

                    <div class="grid gap-4">
                        @foreach(['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d', 'E' => 'option_e'] as $key => $column)
                            @if($currentQuestion->$column)
                                <label
                                    class="group flex items-start p-4 sm:p-5 border-2 rounded-2xl cursor-pointer transition-all duration-200 
                                        {{ isset($answers[$currentQuestion->id]) && $answers[$currentQuestion->id] === $key 
                                            ? 'border-indigo-600 bg-indigo-50/50 shadow-md ring-1 ring-indigo-600 ring-offset-1' 
                                            : 'border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">

                                    <div class="flex items-center h-6 mt-0.5">
                                        <div class="relative flex items-center justify-center w-6 h-6">
                                            <input type="radio" wire:model.live="answers.{{ $currentQuestion->id }}" value="{{ $key }}"
                                                name="question_{{ $currentQuestion->id }}"
                                                class="peer sr-only">
                                            <div class="w-6 h-6 rounded-full border-2 transition-all duration-200 {{ isset($answers[$currentQuestion->id]) && $answers[$currentQuestion->id] === $key ? 'border-indigo-600 bg-indigo-600' : 'border-slate-300 group-hover:border-slate-400' }}"></div>
                                            @if(isset($answers[$currentQuestion->id]) && $answers[$currentQuestion->id] === $key)
                                                <div class="absolute w-2.5 h-2.5 bg-white rounded-full transition-transform scale-100"></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-baseline">
                                            <span class="font-bold text-lg transition-colors {{ isset($answers[$currentQuestion->id]) && $answers[$currentQuestion->id] === $key ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-600' }} mr-3 w-6">{{ $key }}.</span>
                                            <span class="text-slate-700 font-medium text-lg leading-snug">{{ $currentQuestion->$column }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="flex flex-col-reverse sm:flex-row justify-between items-center gap-4">
                <button wire:click="previousQuestion" @if($currentQuestionIndex == 0) disabled @endif
                    class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold transition-all duration-200 flex items-center justify-center gap-2 
                    {{ $currentQuestionIndex == 0 ? 'bg-slate-100 text-slate-400 cursor-not-allowed hidden sm:flex' : 'bg-white text-slate-700 shadow-sm hover:shadow-md border border-slate-200 hover:bg-slate-50 active:scale-95' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Sebelumnya
                </button>

                @if($currentQuestionIndex == count($questions) - 1)
                    <button wire:click="submitExam"
                        wire:confirm="Apakah Anda yakin ingin mengumpulkan ujian ini? Anda tidak bisa mengubah jawaban setelah dikumpulkan."
                        class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold bg-green-500 text-white shadow-lg shadow-green-500/30 hover:bg-green-600 hover:shadow-xl hover:shadow-green-500/40 transition-all active:scale-95 flex items-center justify-center gap-2">
                        Kumpulkan Ujian
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                @else
                    <button wire:click="nextQuestion"
                        class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-700/40 transition-all active:scale-95 flex items-center justify-center gap-2">
                        Selanjutnya
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                @endif
            </div>

        @endif
    </div>
</div>

@if(!$isFinished)
    @script
    <script>
        const elem = document.documentElement;
        let warningActive = false;

        document.addEventListener("visibilitychange", () => {
            if (document.hidden && !warningActive) {
                triggerCheatWarning('Peringatan: Jangan pindah Tab! Pelanggaran dicatat.');
            }
        });

        window.addEventListener("blur", () => {
            if (!warningActive) {
                triggerCheatWarning('Peringatan: Fokus hilang dari layar ujian! Pelanggaran dicatat.');
            }
        });

        function triggerCheatWarning(message) {
            warningActive = true;
            $wire.addCheatWarning();
            
            alert(message);

            setTimeout(() => {
                warningActive = false;
            }, 2000);
        }
    </script>
    @endscript
@endif
