@extends('layouts.app')

@section('title', 'Edit Lahan')

@section('content')

    <div class="mb-6">
        <a href="{{ route('farms.index') }}" class="text-sm text-mint-500 hover:text-mint-700">&larr; Kembali ke Dashboard</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Data Lahan</h1>
        <p class="text-sm text-gray-500 mt-1">Hasil cluster dan estimasi yield akan diperbarui otomatis setelah perubahan disimpan.</p>
    </div>

    <form method="POST" action="{{ route('farms.update', $farm) }}" id="farmForm" class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6">
        @csrf
        @method('PUT')

        @include('farms._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('farms.index') }}" class="px-5 py-2.5 rounded-full text-sm font-medium text-gray-500 border border-gray-200 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" id="submitBtn" class="px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-mint-500 hover:bg-mint-600 shadow-sm disabled:opacity-70 flex items-center gap-2">
                <svg id="spinner" class="hidden animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span id="submitText">Simpan Perubahan</span>
            </button>
        </div>
    </form>

    <script>
        document.getElementById('farmForm').addEventListener('submit', function () {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('spinner').classList.remove('hidden');
            document.getElementById('submitText').textContent = 'Menyimpan...';
        });
    </script>

@endsection
