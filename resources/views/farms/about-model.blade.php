@extends('layouts.app')

@section('title', 'Tentang Model')

@section('content')

    <div class="mb-6">
        <a href="{{ route('farms.dashboard') }}" class="text-sm text-mint-500 hover:text-mint-700">&larr; Kembali ke Dashboard</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Tentang Model</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan teknis model prediksi dan segmentasi yang digunakan pada sistem ini.</p>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6">
        <p class="text-sm text-amber-800">
            <strong>Catatan penting:</strong> Estimasi yang dihasilkan sistem ini bersifat indikatif. Korelasi antara fitur lingkungan/tanah dengan hasil panen pada dataset pelatihan tergolong lemah, sehingga hasil prediksi sebaiknya tidak dijadikan satu-satunya dasar pengambilan keputusan.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

        <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Model Prediksi Hasil Panen</h2>
            <p class="text-xs text-gray-400 mb-4">Random Forest Regressor</p>

            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="bg-mint-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-gray-500">R²</p>
                    <p class="text-lg font-bold text-gray-800">-0.0366</p>
                </div>
                <div class="bg-mint-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-gray-500">RMSE</p>
                    <p class="text-lg font-bold text-gray-800">1196.48</p>
                </div>
                <div class="bg-mint-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-gray-500">MAE</p>
                    <p class="text-lg font-bold text-gray-800">1051.42</p>
                </div>
            </div>

            <ul class="text-sm text-gray-600 space-y-1.5">
                <li>• 14 fitur input (kondisi tanah, lingkungan, dan atribut kategorikal hasil encoding)</li>
                <li>• 100 pohon keputusan (n_estimators), random_state = 42</li>
                <li>• Data latih 400 sampel (80%), data uji 100 sampel (20%)</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Model Segmentasi Lahan</h2>
            <p class="text-xs text-gray-400 mb-4">K-Means Clustering (k=3)</p>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-mint-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-gray-500">Silhouette Score</p>
                    <p class="text-lg font-bold text-gray-800">0.0959</p>
                </div>
                <div class="bg-mint-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-gray-500">Inertia</p>
                    <p class="text-lg font-bold text-gray-800">3305.69</p>
                </div>
            </div>

            <div class="flex gap-2 mb-4">
                <span class="flex-1 text-center text-xs font-medium rounded-full py-1.5 bg-mint-100 text-mint-700">Cluster 0 · 181 lahan</span>
                <span class="flex-1 text-center text-xs font-medium rounded-full py-1.5 bg-amber-100 text-amber-700">Cluster 1 · 183 lahan</span>
                <span class="flex-1 text-center text-xs font-medium rounded-full py-1.5 bg-sky-100 text-sky-700">Cluster 2 · 136 lahan</span>
            </div>

            <p class="text-sm text-gray-600">8 fitur kondisi tanah & lingkungan, distandarisasi (StandardScaler) sebelum clustering. Nilai k=3 dipilih atas dasar interpretabilitas — kenaikan silhouette dari k=3 ke k=8 sangat kecil (hanya 0.019).</p>
        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Keterbatasan Model</h2>
        <ul class="text-sm text-gray-600 space-y-2">
            <li>• Korelasi seluruh fitur lingkungan/tanah terhadap hasil panen sangat lemah (rentang -0.19 hingga 0.09), sehingga model belum mampu menangkap pola prediktif yang kuat.</li>
            <li>• Dataset bersifat sintetis dan menggabungkan 5 komoditas serta 5 wilayah berbeda sekaligus, yang dapat menutupi pola spesifik tiap komoditas/wilayah.</li>
            <li>• Batas antar cluster tidak tegas (Silhouette Score rendah), sehingga penamaan klaster seperti "lahan optimal" atau "lahan kritis" sengaja dihindari.</li>
            <li>• Data spasial (koordinat geografis) tidak digunakan dalam pemodelan, sehingga pola spasial antar lahan belum tereksplorasi.</li>
        </ul>
    </div>

@endsection
