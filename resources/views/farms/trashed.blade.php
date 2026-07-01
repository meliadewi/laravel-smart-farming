@extends('layouts.app')

@section('title', 'Riwayat Terhapus')

@section('content')

    <div class="mb-6">
        <a href="{{ route('farms.index') }}" class="text-sm text-mint-500 hover:text-mint-700">&larr; Kembali ke Data Lahan</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Riwayat Terhapus</h1>
        <p class="text-sm text-gray-500 mt-1">Data lahan yang sudah dihapus. Bisa dipulihkan atau dihapus permanen.</p>
    </div>


    <div class="bg-white rounded-2xl shadow-sm border border-mint-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-mint-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">Nama Lahan</th>
                    <th class="px-5 py-3 text-left">Wilayah</th>
                    <th class="px-5 py-3 text-left">Komoditas</th>
                    <th class="px-5 py-3 text-left">Dihapus Pada</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($farms as $farm)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $farm->farm_name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $farm->region }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ ['Rice'=>'Padi','Maize'=>'Jagung','Wheat'=>'Gandum','Soybean'=>'Kedelai','Cotton'=>'Kapas'][$farm->crop_type] ?? $farm->crop_type }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $farm->deleted_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex gap-2 justify-end">
                                <form method="POST" action="{{ route('farms.restore', $farm->id) }}">
                                    @csrf
                            @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 rounded-full text-xs font-semibold text-white bg-mint-500 hover:bg-mint-600">
                                        Pulihkan
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('farms.force-delete', $farm->id) }}" id="forceDeleteForm{{ $farm->id }}">
                                    @csrf
                                    @method('DELETE')
                            <button type="button" onclick="openDeleteModal('forceDeleteForm{{ $farm->id }}', 'Hapus Permanen?', 'Data lahan {{ $farm->farm_name }} akan dihapus permanen dan tidak bisa dikembalikan.')" class="px-3 py-1.5 rounded-full text-xs font-semibold text-white bg-rose-400 hover:bg-rose-500">
                                        Hapus Permanen
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Tidak ada data lahan yang terhapus.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $farms->links() }}
    </div>

@endsection
