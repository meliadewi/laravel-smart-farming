@extends('layouts.app')

@section('title', $farm->farm_name)

@section('content')

    <div class="mb-6">
        <a href="{{ route('farms.index') }}" class="text-sm text-mint-500 hover:text-mint-700">&larr; Kembali ke Dashboard</a>
        <div class="flex justify-between items-start mt-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $farm->farm_name }}</h1>
                <p class="text-sm text-gray-500">{{ $farm->region }} &middot; {{ $farm->crop_type }}</p>
            </div>
            @if(auth()->user()->isAdmin())
            <div class="flex gap-3">
                <a href="{{ route('farms.edit', $farm) }}" class="px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-blue-400 hover:bg-blue-500 shadow-sm">
                    Edit
                </a>
                <form method="POST" action="{{ route('farms.destroy', $farm) }}" id="deleteFormShow">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="openDeleteModal('deleteFormShow', 'Hapus Lahan?', 'Data lahan {{ $farm->farm_name }} akan dihapus dan bisa dipulihkan dari Riwayat Terhapus.')" class="px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-rose-400 hover:bg-rose-500 shadow-sm">
                        Hapus
                    </button>
                </form>
            </div>
        @endif
        </div>
    </div>

    {{-- Hasil prediksi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-mint-50 border border-mint-100 rounded-2xl p-5">
            <p class="text-sm text-mint-600">Segmentasi Cluster (K-Means)</p>
            @if (!is_null($farm->cluster))
                <p class="text-2xl font-bold text-mint-700 mt-1">
                    Cluster {{ $farm->cluster }}
                    @if ($farm->cluster_label)
                        <span class="block text-base font-medium text-mint-500">{{ $farm->cluster_label }}</span>
                    @endif
                </p>
            @else
                <p class="text-base text-gray-400 mt-1">Belum tersedia — layanan prediksi tidak merespons saat data disimpan.</p>
            @endif
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <p class="text-sm text-blue-700">Estimasi Hasil Panen (Random Forest)</p>
            @if ($farm->predicted_yield)
                <p class="text-2xl font-bold text-blue-800 mt-1">{{ number_format($farm->predicted_yield, 2) }} kg/ha</p>
            @else
                <p class="text-base text-gray-400 mt-1">Belum tersedia — layanan prediksi tidak merespons saat data disimpan.</p>
            @endif
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-3 mb-8 flex items-start gap-2">
        <span class="text-amber-500 text-sm mt-0.5">&#9888;</span>
        <p class="text-xs text-amber-700">Estimasi bersifat indikatif karena korelasi fitur-target pada dataset training tergolong lemah. Lihat <a href="{{ route('farms.about-model') }}" class="underline font-medium">Tentang Model</a> untuk detail lengkap.</p>
    </div>

    {{-- Rekomendasi Otomatis --}}
    <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6 mb-8">
        <h2 class="text-sm font-semibold text-gray-500 uppercase mb-4">Rekomendasi Otomatis</h2>
        <div class="space-y-3">
            @foreach ($recommendations as $rec)
                @php
                    $styles = match ($rec['level']) {
                        'danger' => 'bg-rose-50 border-rose-200 text-rose-700',
                        'warning' => 'bg-amber-50 border-amber-200 text-amber-700',
                        'success' => 'bg-mint-50 border-mint-200 text-mint-700',
                        default => 'bg-sky-50 border-sky-200 text-sky-700',
                    };
                    $icon = match ($rec['level']) {
                        'danger' => '&#9888;',
                        'warning' => '&#9888;',
                        'success' => '&#10003;',
                        default => '&#8505;',
                    };
                @endphp
                <div class="border rounded-xl px-4 py-3 flex items-start gap-2 text-sm {{ $styles }}">
                    <span class="mt-0.5">{!! $icon !!}</span>
                    <p>{{ $rec['message'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Detail atribut lahan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase mb-4">Detail Kondisi Lahan</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-6 text-sm">
            <div>
                <dt class="text-gray-500">Kelembapan Tanah</dt>
                <dd class="font-medium text-gray-800">{{ $farm->soil_moisture }} %</dd>
            </div>
            <div>
                <dt class="text-gray-500">pH Tanah</dt>
                <dd class="font-medium text-gray-800">{{ $farm->soil_pH }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Suhu Udara</dt>
                <dd class="font-medium text-gray-800">{{ $farm->temperature_C }} °C</dd>
            </div>
            <div>
                <dt class="text-gray-500">Curah Hujan</dt>
                <dd class="font-medium text-gray-800">{{ $farm->rainfall_mm }} mm</dd>
            </div>
            <div>
                <dt class="text-gray-500">Kelembapan Udara</dt>
                <dd class="font-medium text-gray-800">{{ $farm->humidity }} %</dd>
            </div>
            <div>
                <dt class="text-gray-500">NDVI Index</dt>
                <dd class="font-medium text-gray-800">{{ $farm->NDVI_index }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Intensitas Sinar Matahari</dt>
                <dd class="font-medium text-gray-800">{{ $farm->sunlight_hours }} jam</dd>
            </div>
            <div>
                <dt class="text-gray-500">Penggunaan Pestisida</dt>
                <dd class="font-medium text-gray-800">{{ $farm->pesticide_usage_ml }} ml</dd>
            </div>
            <div>
                <dt class="text-gray-500">Durasi Masa Tanam</dt>
                <dd class="font-medium text-gray-800">{{ $farm->total_days }} hari</dd>
            </div>
            <div>
                <dt class="text-gray-500">Ditambahkan</dt>
                <dd class="font-medium text-gray-800">{{ $farm->created_at->translatedFormat('d M Y, H:i') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Diperbarui</dt>
                <dd class="font-medium text-gray-800">{{ $farm->updated_at->translatedFormat('d M Y, H:i') }}</dd>
            </div>
        </dl>
    </div>

@endsection
