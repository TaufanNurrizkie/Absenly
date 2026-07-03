@vite('resources/css/app.css')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="{{ asset('img/icb_Logo.png') }}">

<div class="min-h-screen flex items-center justify-center bg-[#EAF4FF] px-4 py-8">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">

        {{-- Kolom Kiri: Branding / Gambar --}}
        <div class="md:w-1/2 flex flex-col items-center justify-center p-10 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 60%, #BAE6FD 100%);">
            {{-- Dekorasi lingkaran latar --}}
            <div class="absolute -top-16 -left-16 w-64 h-64 bg-white/20 rounded-full"></div>
            <div class="absolute -bottom-20 -right-12 w-72 h-72 bg-white/20 rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/10 rounded-full">
            </div>

            <div class="relative z-10 flex flex-col items-center text-center gap-4">
                <img src="{{ asset('img/icb_Logo.png') }}" alt="Logo SMK ICB Cinta Teknika"
                    class="w-40 h-40 sm:w-52 sm:h-52 object-contain drop-shadow-lg">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight drop-shadow">SMK ICB Cinta Teknika</h1>
                <p class="text-white/90 text-sm sm:text-base max-w-xs leading-relaxed">
                    Sistem presensi digital sekolah yang mudah, cepat, dan terpercaya.
                </p>
            </div>
        </div>

        {{-- Kolom Kanan: Form Login --}}
        <div class="md:w-1/2 flex flex-col justify-center p-8 sm:p-10 bg-white">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-[#1D4ED8]">Selamat Datang </h2>
                <p class="text-sm text-[#60A5FA] mt-1">Silakan login menggunakan NIS Anda</p>
            </div>

            {{-- Session Status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- NIS --}}
                <div>
                    <x-input-label for="nis" :value="__('NIS / ID GURU')" class="text-[#1D4ED8]" />
                    <x-text-input id="nis"
                        class="block mt-1 w-full border-[#BAE6FD] focus:border-[#3B82F6] focus:ring-[#3B82F6]"
                        type="text" name="nis" :value="old('nis')" required autofocus />
                    <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                </div>

                {{-- Password --}}
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-[#1D4ED8]" />
                    <x-text-input id="password"
                        class="block mt-1 w-full border-[#BAE6FD] focus:border-[#3B82F6] focus:ring-[#3B82F6]"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Remember Me & Lupa Password --}}
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-[#BAE6FD] text-[#3B82F6] shadow-sm focus:ring-[#3B82F6]"
                            name="remember">
                        <span class="ml-2 text-sm text-[#60A5FA]">{{ __('Ingat saya') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-[#3B82F6] hover:text-[#1D4ED8] hover:underline font-medium"
                            href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                </div>

                {{-- Tombol Login --}}
                <button type="submit"
                    class="w-full h-12 bg-[#3B82F6] hover:bg-[#2563EB] active:bg-[#1D4ED8] transition-colors duration-200 rounded-full text-white font-semibold text-sm tracking-wide shadow-md">
                    {{ __('Login') }}
                </button>
            </form>
        </div>

    </div>
</div>
