@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Lahan Pertanian</h1>
        <a href="{{ route('farms.index') }}" class="bg-white border border-mint-300 text-mint-700 hover:bg-mint-50 px-5 py-2.5 rounded-full text-sm font-semibold shadow-sm transition">
            Lihat Data Lahan
        </a>
    </div>

    {{-- Alert Lahan Kritis --}}
    @if ($criticalFarms->isNotEmpty())
        <div class="bg-rose-50 border border-rose-200 rounded-xl px-5 py-4 mb-8">
            <div class="flex items-start gap-2 mb-2">
                <span class="text-rose-500 text-sm mt-0.5">&#9888;</span>
                <p class="text-sm font-semibold text-rose-700">{{ $criticalFarms->count() }} lahan memerlukan perhatian segera</p>
            </div>
            <ul class="ml-6 space-y-1">
                @foreach ($criticalFarms as $cf)
                    <li class="text-sm text-rose-700">
                        <a href="{{ route('farms.show', $cf) }}" class="underline font-medium">{{ $cf->farm_name }}</a>
                        <span class="text-rose-500">— {{ $cf->region }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-5">
            <p class="text-sm text-gray-500">Total Lahan</p>
            <p class="text-3xl font-bold text-mint-700 mt-1">{{ $total }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-5">
            <p class="text-sm text-gray-500">Rata-rata Yield</p>
            <p class="text-3xl font-bold text-mint-700 mt-1">
                {{ $avgYield ? number_format($avgYield, 2) : '—' }}
                <span class="text-base font-medium text-gray-400">kg/ha</span>
            </p>
        </div>

        @forelse ($clusters as $c)
            <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-5">
                <p class="text-sm text-gray-500">
                    Cluster {{ $c->cluster }}
                    @if ($c->cluster_label)
                        <span class="text-gray-400">— {{ $c->cluster_label }}</span>
                    @endif
                </p>
                <p class="text-3xl font-bold text-mint-700 mt-1">{{ $c->total }}</p>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-5 sm:col-span-2">
                <p class="text-sm text-gray-400">Belum ada data cluster. Tambah data lahan untuk melihat hasil segmentasi.</p>
            </div>
        @endforelse
    </div>

    {{-- Grafik: rata-rata yield per komoditas & distribusi cluster --}}
    @if ($yieldByCrop->isNotEmpty() || $clusters->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @if ($yieldByCrop->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-600 mb-4">Rata-rata Yield per Jenis Tanaman</h2>
                    <canvas id="yieldChart" height="220"></canvas>
                </div>
            @endif

            @if ($clusters->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-600 mb-4">Distribusi Cluster Lahan</h2>
                    <canvas id="clusterChart" height="220"></canvas>
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-10 text-center text-gray-400">
            Belum ada data untuk ditampilkan dalam grafik. @if(auth()->user()->isAdmin())<a href="{{ route('farms.index') }}" class="text-mint-600 underline">Tambah data lahan</a>@endif untuk mulai melihat visualisasi.
        </div>
    @endif

    @if ($yieldByCrop->isNotEmpty() || $clusters->isNotEmpty())
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <script>
            @if ($yieldByCrop->isNotEmpty())
                new Chart(document.getElementById('yieldChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($yieldByCrop->pluck('crop_type')),
                        datasets: [{
                            label: 'Rata-rata Yield (kg/ha)',
                            data: @json($yieldByCrop->pluck('avg_yield')),
                            backgroundColor: '#5FC890',
                            borderRadius: 8,
                            maxBarThickness: 60,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F0FBF6' } },
                            x: { grid: { display: false } },
                        },
                    },
                });
            @endif

            @if ($clusters->isNotEmpty())
                new Chart(document.getElementById('clusterChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($clusters->map(fn($c) => $c->cluster_label ?? ('Cluster ' . $c->cluster))),
                        datasets: [{
                            data: @json($clusters->pluck('total')),
                            backgroundColor: ['#5FC890', '#3DAF73', '#8FDCB3', '#2E8F5C', '#BDEBD2'],
                            borderWidth: 0,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
                        },
                    },
                });
            @endif
        </script>
    @endif

@endsection
