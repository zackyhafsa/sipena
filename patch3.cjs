const fs = require('fs');
let content = fs.readFileSync('resources/views/livewire/student-exam.blade.php', 'utf8');

// Replace the modal part
let newModal = `<!-- Modal Peringatan Kecurangan -->
    <template x-teleport="body">
        <div x-show="showWarning" style="display: none;" class="fixed inset-0 z-[110] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showWarning" x-transition.opacity
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    @click="showWarning = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showWarning" x-transition.scale
                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 relative z-[120]">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Peringatan Keamanan Ujian!</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Anda terdeteksi melanggar aturan dengan keluar dari layar penuh / berpindah tab.</p>
                                <p class="mt-2 text-sm font-bold text-red-600">Pelanggaran: <span x-text="$wire.violationCount"></span> dari <span x-text="$wire.maxViolations"></span></p>
                                <p class="mt-2 text-sm text-gray-500">Jika jumlah pelanggaran mencapai batas maksimum, ujian Anda akan <strong class="text-red-600">dihentikan secara otomatis!</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse relative z-50">
                        <button type="button" @click="showWarning = false; isProcessingViolation = false; enterFullscreen();"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Saya Mengerti & Tidak Mengulangi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>`;

content = content.replace(/<!-- Modal Peringatan Kecurangan -->[\s\S]*?<\/template>\s*<\/div>/, newModal);
console.log("Replaced modal HTML");

// Replace script block
let newScript = `@script
    <script>
        Alpine.data('examSecurity', () => ({
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
                
                // Add event listeners using Livewire 3 syntax
                $wire.on('show-violation-warning', () => {
                    this.showWarning = true;
                });
                
                $wire.on('show-fatal-warning', () => {
                    $wire.forceSubmit();
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
                
                // Call backend immediately
                await $wire.registerViolation();
            }
        }));
    </script>
    @endscript`;

content = content.replace(/@script[\s\S]*?@endscript/, newScript);
fs.writeFileSync('resources/views/livewire/student-exam.blade.php', content);
