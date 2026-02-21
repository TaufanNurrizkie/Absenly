@vite('resources/css/app.css')
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-900 px-4 py-6 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sm:p-8">
        <div class="text-center mb-6">
            <img src="{{ asset('img/Shiny Happy - Standing.png') }}" alt="Logo Sekolah"
                class="w-28 h-28 sm:w-40 sm:h-40 mx-auto mb-4 object-contain">
            <h1 class="text-xl sm:text-4xl font-bold text-[#1a3581]">Absenly</h1>
            <p class="text-sm text-gray-500">Silakan login menggunakan NIS Anda</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- NIS -->
            <div class="mb-4">
                <x-input-label for="nis" :value="__('NIS')" />
                <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis" :value="old('nis')" required autofocus />
                <x-input-error :messages="$errors->get('nis')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-[#1E3A8A] shadow-sm focus:ring-[#1E3A8A]"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-[#1E3A8A] hover:underline" href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>

            <button class="w-full h-12 justify-center bg-[#1E3A8A] hover:bg-[#1c2f74] rounded-full text-white font-semibold  ">
                {{ __('Login') }}
            </button>
        </form>
    </div>
</div>
