<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Buat Akun Baru</h2>
    <p class="text-sm text-gray-400 mb-8">Mulai kelola lahan pertanianmu secara cerdas.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama')" class="text-gray-600 font-medium" />
            <x-text-input id="name"
                class="block mt-1 w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-600 font-medium" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-600 font-medium" />
            <x-text-input id="password"
                class="block mt-1 w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-600 font-medium" />
            <x-text-input id="password_confirmation"
                class="block mt-1 w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
            class="w-full rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 transition">
            {{ __('Daftar') }}
        </button>

        <p class="text-center text-sm text-gray-500 pt-2">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-emerald-600 font-semibold hover:underline">Masuk</a>
        </p>
    </form>
</x-guest-layout>
