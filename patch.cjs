const fs = require('fs');
let content = fs.readFileSync('resources/views/livewire/student-exam.blade.php', 'utf8');

// 1. Add x-data logic and exam overlay
content = content.replace('<div class="max-w-4xl mx-auto pb-20">', `<div class="max-w-4xl mx-auto pb-20" x-data="examSecurity()">
    <!-- Fullscreen Overlay -->
    <template x-teleport="body">
        <div x-show="!isFullscreen" class="fixed inset-0 z-[100] bg-gray-900 bg-opacity-95 flex items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Mode Layar Penuh Diperlukan</h2>
                <p class="text-gray-600 mb-6">Anda keluar dari mode layar penuh (fullscreen) atau memindahkan tab. Ujian hanya dapat dilanjutkan dalam mode layar penuh untuk mencegah kecurangan.</p>
                <button type="button" @click="enterFullscreen()" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-md text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 transition-all transform active:scale-95">
                    Masuk Layar Penuh & Lanjutkan
                </button>
            </div>
        </div>
    </template>`);

// 2. Add Alpine component data in script block
let scriptBlock = `@script
    <script>
        Alpine.data('examSecurity', () => ({
            isFullscreen: false,
            init() {
                // Check if already fullscreen
                this.isFullscreen = !!document.fullscreenElement;
                
                document.addEventListener('fullscreenchange', () => {
                    this.isFullscreen = !!document.fullscreenElement;
                    if (!this.isFullscreen) {
                        this.handleViolation();
                    }
                });

                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        this.handleViolation();
                    }
                });

                window.addEventListener('blur', () => {
                    this.handleViolation();
                });
            },
            enterFullscreen() {
                let elem = document.documentElement;
                if (elem.requestFullscreen) {
                    elem.requestFullscreen().then(() => {
                        this.isFullscreen = true;
                    }).catch(err => {
                        alert("Browser Anda menolak mode layar penuh. Tolong izinkan.");
                    });
                }
            },
            isProcessingViolation: false,
            timeSinceLoad: Date.now(),
            handleViolation() {
                if (this.isProcessingViolation || Date.now() - this.timeSinceLoad < 3000) return;
                
                this.isProcessingViolation = true;
                $wire.registerViolation();

                setTimeout(() => {
                    this.isProcessingViolation = false;
                }, 3000);
            }
        }));
    </script>
    @endscript`;

content = content.replace(/@script[\s\S]*?@endscript/, scriptBlock);

fs.writeFileSync('resources/views/livewire/student-exam.blade.php', content);
