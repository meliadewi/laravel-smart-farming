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

                <div class="flex items-center gap-1 text-sm divide-x divide-mint-100">
                    <a href="{{ route('farms.dashboard') }}" class="px-3 text-gray-500 hover:text-mint-600 transition {{ request()->routeIs('farms.dashboard') ? 'text-mint-600 font-semibold border-b-2 border-mint-500 pb-1' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('farms.index') }}" class="px-3 text-gray-500 hover:text-mint-600 transition {{ request()->routeIs('farms.index') ? 'text-mint-600 font-semibold border-b-2 border-mint-500 pb-1' : '' }}">
                        Data Lahan
                    </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('farms.create') }}" class="px-3 text-gray-500 hover:text-mint-600 transition {{ request()->routeIs('farms.create') ? 'text-mint-600 font-semibold border-b-2 border-mint-500 pb-1' : '' }}">
                        Tambah Lahan
                    </a>
                @endif
                        <a href="{{ route('farms.compare.form') }}" class="px-3 text-gray-500 hover:text-mint-600 transition {{ request()->routeIs('farms.compare.*') ? 'text-mint-600 font-semibold border-b-2 border-mint-500 pb-1' : '' }}">
                            Bandingkan Lahan
                        </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('farms.trashed') }}" class="px-3 text-gray-500 hover:text-mint-600 transition {{ request()->routeIs('farms.trashed') ? 'text-mint-600 font-semibold border-b-2 border-mint-500 pb-1' : '' }}">
                            Riwayat Terhapus
                        </a>
                    @endif

                    @auth
                        <span class="text-mint-700 font-medium">
                            {{ auth()->user()->name }}
                            <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-mint-100 text-mint-600">
                                {{ auth()->user()->isAdmin() ? 'Admin' : 'Viewer' }}
                            </span>
                        </span>
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


    <!-- Custom Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4 animate-in fade-in zoom-in duration-200">
            <div class="flex flex-col items-center text-center gap-3">
                <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-800" id="deleteModalTitle">Hapus Data?</h3>
                <p class="text-sm text-gray-500" id="deleteModalMessage">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="flex gap-3 mt-6">
                <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button id="deleteModalConfirm"
                    class="flex-1 px-4 py-2 rounded-xl bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold transition">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(formId, title, message) {
            document.getElementById('deleteModalTitle').textContent = title || 'Hapus Data?';
            document.getElementById('deleteModalMessage').textContent = message || 'Tindakan ini tidak dapat dibatalkan.';
            document.getElementById('deleteModalConfirm').onclick = function() {
                document.getElementById(formId).submit();
            };
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

</body>
</html>
