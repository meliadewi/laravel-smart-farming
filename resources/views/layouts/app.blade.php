<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Farming') | Smart Farming</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        mint: {
                            50:  '#F0FBF6',
                            100: '#DCF5E8',
                            200: '#BDEBD2',
                            300: '#8FDCB3',
                            400: '#5FC890',
                            500: '#3DAF73',
                            600: '#2E8F5C',
                            700: '#26714A',
                            800: '#1F5A3C',
                        },
                    },
                },
            },
        };
    </script>
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-mint-50 min-h-screen">

    <nav class="bg-white border-b border-mint-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('farms.index') }}" class="font-bold text-lg flex items-center gap-2 text-mint-700">
                    Smart Farming
                </a>

                <div class="flex items-center gap-5 text-sm">
                    <a href="{{ route('farms.index') }}" class="text-gray-500 hover:text-mint-600 transition {{ request()->routeIs('farms.index') ? 'text-mint-600 font-semibold border-b-2 border-mint-500 pb-1' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('farms.create') }}" class="text-gray-500 hover:text-mint-600 transition {{ request()->routeIs('farms.create') ? 'text-mint-600 font-semibold border-b-2 border-mint-500 pb-1' : '' }}">
                        Tambah Lahan
                    </a>

                    @auth
                        <span class="text-mint-700 font-medium">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="border border-mint-300 text-mint-700 hover:bg-mint-100 px-4 py-1.5 rounded-full transition font-medium">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if (session('success'))
            <div class="mb-6 rounded-xl bg-mint-100 border border-mint-200 text-mint-800 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm">
                <p class="font-semibold mb-1">Periksa kembali input Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

</body>
</html>
