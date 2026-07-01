@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-semibold mb-4">Hasil Perbandingan Lahan</h1>

        <canvas id="compareChart" class="mb-6"></canvas>

        <table class="w-full text-sm border-collapse mb-6">
            <thead>
                <tr class="bg-mint-100 text-left">
                    <th class="p-2">Nama Lahan</th>
                    <th class="p-2">Yield</th>
                    <th class="p-2">Cluster</th>
                </tr>
            </thead>
            <tbody>
                @foreach($farms as $farm)
                <tr class="border-b">
                    <td class="p-2">{{ $farm->farm_name }}</td>
                    <td class="p-2">{{ $farm->predicted_yield }}</td>
                    <td class="p-2">
                        @php
                            $clusterColor = ['mint','amber','sky'][$farm->cluster] ?? 'gray';
                        @endphp
                        <span class="px-2 py-1 rounded text-xs bg-{{ $clusterColor }}-200">
                            {{ $farm->cluster_label }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('farms.compare.form') }}" class="text-sm text-gray-500 hover:underline">← Pilih lahan lain</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('compareChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($farms->pluck('farm_name')) !!},
                datasets: [{
                    label: 'Yield',
                    data: {!! json_encode($farms->pluck('predicted_yield')) !!},
                    backgroundColor: ['#6EE7B7', '#FCD34D', '#7DD3FC']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
    </script>
@endsection
