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
               placeholder="Contoh: Tabanan" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Komoditas</label>
        <select name="crop_type" required
                class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400 bg-white appearance-none">
            <option value="">-- Pilih Komoditas --</option>
            @foreach(['Wheat' => 'Gandum', 'Maize' => 'Jagung', 'Rice' => 'Padi', 'Soybean' => 'Kedelai', 'Cotton' => 'Kapas'] as $val_en => $label_id)
                <option value="{{ $val_en }}" {{ $val('crop_type') === $val_en ? 'selected' : '' }}>
                    {{ $label_id }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kelembapan Tanah (%)</label>
        <input type="number" step="0.01" min="20" max="99" name="soil_moisture" placeholder="20–99" value="{{ $val('soil_moisture') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">pH Tanah</label>
        <input type="number" step="0.01" min="3" max="12" name="soil_pH" placeholder="3–12" value="{{ $val('soil_pH') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Suhu Udara (°C)</label>
        <input type="number" step="0.01" min="15" max="35" name="temperature_C" placeholder="15–35" value="{{ $val('temperature_C') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Curah Hujan (mm)</label>
        <input type="number" step="0.01" min="34" max="1234" name="rainfall_mm" placeholder="34–1234" value="{{ $val('rainfall_mm') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kelembapan Udara (%)</label>
        <input type="number" step="0.01" min="30" max="89" name="humidity" placeholder="30–89" value="{{ $val('humidity') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">NDVI Index (0–1)</label>
        <input type="number" step="0.01" min="0.09" max="1" name="NDVI_index" placeholder="0.09–1" value="{{ $val('NDVI_index') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Intensitas Sinar Matahari (jam)</label>
        <input type="number" step="0.01" min="6" max="12" name="sunlight_hours" placeholder="6–12" value="{{ $val('sunlight_hours') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Penggunaan Pestisida (ml)</label>
        <input type="number" step="0.01" min="46" max="7655" name="pesticide_usage_ml" placeholder="46–7655" value="{{ $val('pesticide_usage_ml') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Durasi Masa Tanam (hari)</label>
        <input type="number" step="1" min="28" max="678" name="total_days" placeholder="28–678" value="{{ $val('total_days') }}"
               class="w-full rounded-xl border border-mint-200 px-3 py-2 text-sm focus:ring-2 focus:ring-mint-400 focus:border-mint-400" required>
    </div>

</div>
