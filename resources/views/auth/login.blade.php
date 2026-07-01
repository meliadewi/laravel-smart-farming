<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Masuk ke Akun</h2>
    <p class="text-sm text-gray-400 mb-8">Pantau lahan dan hasil panenmu di satu tempat.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-600 font-medium" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-600 font-medium" />
            <x-text-input id="password"
                class="block mt-1 w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me + Forgot -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-sm text-gray-500">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-600 hover:text-emerald-700 hover:underline"
                    href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <button type="submit"
            class="w-full rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 transition">
            {{ __('Masuk') }}
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500 pt-2">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-emerald-600 font-semibold hover:underline">Daftar</a>
            </p>
        @endif
    </form>
</x-guest-layout>
