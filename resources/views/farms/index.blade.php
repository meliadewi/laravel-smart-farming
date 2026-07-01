@extends('layouts.app')

@section('title', 'Data Lahan')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Lahan Pertanian</h1>
        <div class="flex gap-3">
            <a href="{{ route('farms.export') }}" class="bg-white border border-mint-300 text-mint-700 hover:bg-mint-50 px-5 py-2.5 rounded-full text-sm font-semibold shadow-sm transition">
                Export CSV
            </a>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('farms.create') }}" class="bg-mint-500 hover:bg-mint-600 text-white px-5 py-2.5 rounded-full text-sm font-semibold shadow-sm transition">
                    + Tambah Lahan
                </a>
            @endif
        </div>
    </div>

    {{-- Pencarian dan filter --}}
    <form method="GET" action="{{ route('farms.index') }}" class="flex flex-col sm:flex-row gap-3 mb-4">
        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Cari nama lahan atau wilayah..."
            class="flex-1 rounded-xl border border-mint-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400"
        >
        <select name="crop_type" class="rounded-xl border border-mint-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400">
            <option value="">Semua Tanaman</option>
            @foreach ($cropTypes as $crop)
                <option value="{{ $crop }}" {{ $cropFilter === $crop ? 'selected' : '' }}>{{ $crop }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-mint-500 hover:bg-mint-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
            Cari
        </button>
        @if ($search || $cropFilter)
            <a href="{{ route('farms.index') }}" class="text-sm text-gray-400 hover:text-gray-600 px-3 py-2.5 whitespace-nowrap">
                Reset
            </a>
        @endif
    </form>

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
                        <th class="px-4 py-3 text-left">Dibuat Oleh</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-mint-50">
                    @forelse ($farms as $farm)
                        <tr class="hover:bg-mint-50/60 transition-colors">
                            <td class="px-4 py-3 text-gray-500">{{ $loop->iteration + ($farms->currentPage() - 1) * $farms->perPage() }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $farm->farm_name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $farm->region }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ ['Rice'=>'Padi','Maize'=>'Jagung','Wheat'=>'Gandum','Soybean'=>'Kedelai','Cotton'=>'Kapas'][$farm->crop_type] ?? $farm->crop_type }}
                            </td>
                            <td class="px-4 py-3">
                                @if (!is_null($farm->cluster))
                                    @php
                                        $clusterColors = [
                                            0 => 'bg-mint-100 text-mint-700',
                                            1 => 'bg-amber-100 text-amber-700',
                                            2 => 'bg-sky-100 text-sky-700',
                                        ];
                                        $colorClass = $clusterColors[$farm->cluster] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ $farm->cluster_label ?? ('Cluster ' . $farm->cluster) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">Belum diproses</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $farm->predicted_yield ? number_format($farm->predicted_yield, 2) . ' kg/ha' : '—' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                @if($farm->creator)
                                    <span title="Dibuat: {{ $farm->created_at->format('d M Y H:i') }}">
                                        {{ $farm->creator->name }}
                                    </span>
                                    <span class="block text-gray-400">{{ $farm->created_at->diffForHumans() }}</span>
                                    @if($farm->updater && $farm->updated_by !== $farm->created_by)
                                        <span class="block text-amber-600 mt-0.5" title="Terakhir diubah: {{ $farm->updated_at->format('d M Y H:i') }}">
                                            ✎ diubah oleh {{ $farm->updater->name }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400">–</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-3 text-sm">
                                    <a href="{{ route('farms.show', $farm) }}" class="text-mint-600 hover:text-mint-800">Detail</a>
                                    @if (auth()->user()->isAdmin())
                                        <a href="{{ route('farms.edit', $farm) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                        <form method="POST" action="{{ route('farms.destroy', $farm) }}" id="deleteForm{{ $farm->id }}">
                                            @csrf
                                            @method('DELETE')
                            <button type="button" onclick="openDeleteModal('deleteForm{{ $farm->id }}', 'Hapus Lahan?', 'Data lahan {{ $farm->farm_name }} akan dihapus dan bisa dipulihkan dari Riwayat Terhapus.')" class="text-rose-400 hover:text-rose-600">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                @if ($search || $cropFilter)
                                    Tidak ada lahan yang cocok dengan pencarian. <a href="{{ route('farms.index') }}" class="text-mint-600 underline">Reset filter</a>.
                                @else
                                    Belum ada data lahan.
                                    @if (auth()->user()->isAdmin())
                                        <a href="{{ route('farms.create') }}" class="text-mint-600 underline">Tambah data pertama</a>.
                                    @endif
                                @endif
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

@endsection
