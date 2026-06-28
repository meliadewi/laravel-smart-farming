@php
    // $farm tersedia saat edit, null saat create
    $val = fn($field, $default = '') => old($field, $farm->{$field} ?? $default);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lahan</label>
        <input type="text" name="farm_name" value="{{ $val('farm_name') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400"
               placeholder="Contoh: Lahan Sawah Blok A" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
        <input type="text" name="region" value="{{ $val('region') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400"
               placeholder="Contoh: Jawa Timur" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Komoditas</label>
        <input type="text" name="crop_type" value="{{ $val('crop_type') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400"
               placeholder="Contoh: Padi" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kelembapan Tanah (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="soil_moisture" value="{{ $val('soil_moisture') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">pH Tanah</label>
        <input type="number" step="0.01" min="0" max="14" name="soil_pH" value="{{ $val('soil_pH') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Suhu Udara (°C)</label>
        <input type="number" step="0.01" min="-10" max="60" name="temperature_C" value="{{ $val('temperature_C') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Curah Hujan (mm)</label>
        <input type="number" step="0.01" min="0" name="rainfall_mm" value="{{ $val('rainfall_mm') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kelembapan Udara (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="humidity" value="{{ $val('humidity') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">NDVI Index (0–1)</label>
        <input type="number" step="0.01" min="0" max="1" name="NDVI_index" value="{{ $val('NDVI_index') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Intensitas Sinar Matahari (jam)</label>
        <input type="number" step="0.01" min="0" max="24" name="sunlight_hours" value="{{ $val('sunlight_hours') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Penggunaan Pestisida (ml)</label>
        <input type="number" step="0.01" min="0" name="pesticide_usage_ml" value="{{ $val('pesticide_usage_ml') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Durasi Masa Tanam (hari)</label>
        <input type="number" step="1" min="1" name="total_days" value="{{ $val('total_days') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

</div>
