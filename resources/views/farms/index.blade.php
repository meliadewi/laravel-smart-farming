@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Lahan Pertanian</h1>
        <a href="{{ route('farms.create') }}" class="bg-mint-500 hover:bg-mint-600 text-white px-5 py-2.5 rounded-full text-sm font-semibold shadow-sm transition">
            + Tambah Lahan
        </a>
    </div>

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

    {{-- Bar chart rata-rata yield per komoditas --}}
    @if ($yieldByCrop->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6 mb-8">
            <h2 class="text-sm font-semibold text-gray-600 mb-4">Rata-rata Yield per Jenis Tanaman</h2>
            <canvas id="yieldChart" height="100"></canvas>
        </div>
    @endif

    {{-- Tabel data lahan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-mint-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-mint-50 text-mint-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Nama Lahan</th>
                        <th class="px-4 py-3 text-left">Wilayah</th>
                        <th class="px-4 py-3 text-left">Komoditas</th>
                        <th class="px-4 py-3 text-left">Cluster</th>
                        <th class="px-4 py-3 text-left">Estimasi Yield</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-mint-50">
                    @forelse ($farms as $farm)
                        <tr class="hover:bg-mint-50/60 transition-colors">
                            <td class="px-4 py-3 text-gray-500">{{ $loop->iteration + ($farms->currentPage() - 1) * $farms->perPage() }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $farm->farm_name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $farm->region }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $farm->crop_type }}</td>
                            <td class="px-4 py-3">
                                @if (!is_null($farm->cluster))
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-mint-100 text-mint-700">
                                        {{ $farm->cluster_label ?? ('Cluster ' . $farm->cluster) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">Belum diproses</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $farm->predicted_yield ? number_format($farm->predicted_yield, 2) . ' kg/ha' : '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-3 text-sm">
                                    <a href="{{ route('farms.show', $farm) }}" class="text-mint-600 hover:text-mint-800">Detail</a>
                                    <a href="{{ route('farms.edit', $farm) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <form method="POST" action="{{ route('farms.destroy', $farm) }}" onsubmit="return confirm('Yakin ingin menghapus data lahan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                Belum ada data lahan. <a href="{{ route('farms.create') }}" class="text-mint-600 underline">Tambah data pertama</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($farms->hasPages())
            <div class="px-4 py-3 border-t border-mint-100">
                {{ $farms->links() }}
            </div>
        @endif
    </div>

    @if ($yieldByCrop->isNotEmpty())
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
        <script>
            const ctx = document.getElementById('yieldChart');
            new Chart(ctx, {
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
        </script>
    @endif

@endsection

