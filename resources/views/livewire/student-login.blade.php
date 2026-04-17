<div class="min-h-[80vh] flex items-center justify-center p-4">
    <div class="max-w-md w-full animate-fade-in-up">
        <!-- Logo/Header Component -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-100 rounded-full mb-4 shadow-sm">
                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Portal CBT Edukasi</h2>
            <p class="mt-2 text-sm text-gray-600">Silakan login untuk mengakses daftar ujian aktif anda.</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-gray-100 sm:px-10 relative overflow-hidden">
            <!-- Decorative blur element -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

            <form wire:submit.prevent="login" class="space-y-6 relative z-10">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input wire:model="email" id="email" type="email" required class="block w-full pl-10 sm:text-sm border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3 transition-colors" placeholder="siswa@sekolah.com">
                    </div>
                    @error('email') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input wire:model="password" id="password" type="password" required class="block w-full pl-10 sm:text-sm border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3 transition-colors" placeholder="••••••••">
                    </div>
                    @error('password') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform active:scale-[0.98]">
                        Masuk Sistem
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Info -->
        <div class="mt-8 text-center sm:max-w-sm mx-auto">
            <p class="text-xs text-gray-500 bg-gray-100 p-3 rounded-lg border border-gray-200">
                Lupa kata sandi atau tidak bisa masuk? Silakan hubungi proktor/guru untuk mendapatkan akses.
            </p>
        </div>
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
