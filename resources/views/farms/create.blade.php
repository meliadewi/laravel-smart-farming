@extends('layouts.app')

@section('title', 'Tambah Lahan')

@section('content')

    <div class="mb-6">
        <a href="{{ route('farms.index') }}" class="text-sm text-mint-500 hover:text-mint-700">&larr; Kembali ke Dashboard</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Tambah Data Lahan</h1>
        <p class="text-sm text-gray-500 mt-1">Hasil cluster dan estimasi yield akan dihitung otomatis oleh layanan prediksi setelah data disimpan.</p>
    </div>

    <form method="POST" action="{{ route('farms.store') }}" class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6">
        @csrf

        @php $farm = null; @endphp
        @include('farms._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('farms.index') }}" class="px-5 py-2.5 rounded-full text-sm font-medium text-gray-500 border border-gray-200 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-mint-500 hover:bg-mint-600 shadow-sm">
                Simpan Data
            </button>
        </div>
    </form>

@endsection
