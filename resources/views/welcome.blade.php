<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Farming — Sistem Manajemen & Prediksi Lahan Pertanian</title>
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
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: 0, transform: 'translateY(12px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        },
                    },
                    animation: {
                        fadeUp: 'fadeUp 0.7s ease-out forwards',
                    },
                },
            },
        };
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .delay-1 { animation-delay: .1s; opacity: 0; }
        .delay-2 { animation-delay: .25s; opacity: 0; }
        .delay-3 { animation-delay: .4s; opacity: 0; }
    </style>
</head>
<body class="bg-gradient-to-b from-mint-50 via-mint-50 to-white min-h-screen flex flex-col">

    <main class="flex-1 flex flex-col items-center justify-center text-center px-6 py-20">

        <h1 class="text-5xl sm:text-6xl font-extrabold text-mint-700 animate-fadeUp delay-1">
            Smart Farming
        </h1>

        <p class="mt-4 text-lg sm:text-xl text-gray-500 italic animate-fadeUp delay-2">
            Sistem Manajemen &amp; Prediksi Lahan Pertanian Cerdas
        </p>

        <p class="mt-3 max-w-xl text-gray-500 animate-fadeUp delay-2">
            Pantau kondisi lahan, kelola data pertanian, dan dapatkan prediksi segmentasi serta
            estimasi hasil panen secara otomatis dalam satu platform.
        </p>

        <a href="{{ route('login') }}"
           class="mt-8 inline-flex items-center gap-2 bg-mint-500 hover:bg-mint-600 text-white font-semibold rounded-full px-8 py-3 shadow-lg shadow-mint-200 transition animate-fadeUp delay-3">
            Masuk ke Dashboard
            <span>&rarr;</span>
        </a>

        <div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl w-full animate-fadeUp delay-3">
            <div class="bg-white/70 rounded-2xl border border-mint-100 p-5">
                <p class="text-sm font-semibold text-mint-700">Manajemen Data</p>
                <p class="text-xs text-gray-400 mt-1">Catat & kelola kondisi lahan dengan mudah</p>
            </div>
            <div class="bg-white/70 rounded-2xl border border-mint-100 p-5">
                <p class="text-sm font-semibold text-mint-700">Segmentasi Cerdas</p>
                <p class="text-xs text-gray-400 mt-1">K-Means mengelompokkan kualitas lahan</p>
            </div>
            <div class="bg-white/70 rounded-2xl border border-mint-100 p-5">
                <p class="text-sm font-semibold text-mint-700">Prediksi Hasil Panen</p>
                <p class="text-xs text-gray-400 mt-1">Estimasi yield via Random Forest</p>
            </div>
        </div>

    </main>

    <footer class="text-center pb-6 text-xs text-gray-400">
        Proyek Akhir &mdash; Pemrograman Web Framework &amp; Data Mining
    </footer>

</body>
</html>
