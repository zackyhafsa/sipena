<div class="min-h-[80vh] flex items-center justify-center p-4">
    <div class="max-w-md w-full animate-fade-in-up">
        <!-- Logo/Header Component -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-4 shadow-sm border border-gray-100">
                <img src="{{ asset('logo/logo.png') }}" class="w-16 h-16 object-contain" alt="Logo SIPENA">
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Portal CBT Edukasi</h2>
            <p class="mt-2 text-sm text-gray-600">Silakan login untuk mengakses daftar ujian aktif anda.</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-gray-100 sm:px-10 relative overflow-hidden">
            <!-- Decorative blur element -->
            <div
                class="absolute -top-10 -right-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none">
            </div>

            <form wire:submit.prevent="login" class="space-y-6 relative z-10">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input wire:model="email" id="email" type="email" required
                            class="block w-full pl-10 sm:text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 py-3 transition-colors"
                            placeholder="siswa@sipena.com">
                    </div>
                    @error('email') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <div class="mt-1 relative rounded-md shadow-sm" x-data="{ showPassword: false }">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input wire:model="password" id="password" x-bind:type="showPassword ? 'text' : 'password'"
                            required
                            class="block w-full pl-10 pr-10 sm:text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 py-3 transition-colors"
                            placeholder="••••••••">

                        <!-- Toggle Password Button -->
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform active:scale-[0.98]">
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