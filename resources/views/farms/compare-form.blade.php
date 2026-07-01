@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-semibold mb-4">Bandingkan Lahan</h1>
        <p class="text-sm text-gray-500 mb-4">Pilih 2–3 lahan untuk dibandingkan.</p>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('farms.compare.result') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-2 mb-4">
                @forelse($farms as $farm)
                <label class="flex items-center gap-2 border rounded p-2">
                    <input type="checkbox" name="farm_ids[]" value="{{ $farm->id }}">
                    {{ $farm->farm_name }}
                </label>
                @empty
                <p class="text-gray-500 col-span-2">Belum ada data lahan.</p>
                @endforelse
            </div>
            <button type="submit" class="bg-mint-500 text-white px-4 py-2 rounded">Bandingkan</button>
        </form>
    </div>
@endsection
