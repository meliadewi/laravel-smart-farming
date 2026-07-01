<?php

namespace App\Http\Controllers;
use App\Services\RecommendationService;

use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FarmController extends Controller
{
    public function dashboard()
    {
        $total = Farm::count();

        $clusters = Farm::whereNotNull('cluster')
            ->selectRaw('cluster, cluster_label, count(*) as total')
            ->groupBy('cluster', 'cluster_label')
            ->get();

        $avgYield = Farm::whereNotNull('predicted_yield')->avg('predicted_yield');

        $yieldByCrop = Farm::whereNotNull('predicted_yield')
            ->selectRaw('crop_type, avg(predicted_yield) as avg_yield')
            ->groupBy('crop_type')
            ->orderBy('crop_type')
            ->get();

        $criticalFarms = Farm::all()->filter(function ($farm) {
            return collect(RecommendationService::generate($farm))->contains('level', 'danger');
        })->take(5);

        $cropLabels = ['Rice'=>'Padi','Maize'=>'Jagung','Wheat'=>'Gandum','Soybean'=>'Kedelai','Cotton'=>'Kapas'];
        $yieldByCrop = $yieldByCrop->map(function($item) use ($cropLabels) {
            $item->crop_type = $cropLabels[$item->crop_type] ?? $item->crop_type;
            return $item;
        });

        return view('farms.dashboard', compact('total', 'clusters', 'avgYield', 'yieldByCrop', 'criticalFarms'));
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $cropFilter = $request->query('crop_type');

        $query = Farm::query()->with(['creator', 'updater']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('farm_name', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
            });
        }

        if ($cropFilter) {
            $query->where('crop_type', $cropFilter);
        }

        $farms = $query->latest()->paginate(10)->withQueryString();

        $cropTypes = Farm::select('crop_type')->distinct()->orderBy('crop_type')->pluck('crop_type');

        return view('farms.index', compact('farms', 'cropTypes', 'search', 'cropFilter'));
    }

    public function export()
    {
        $farms = Farm::orderBy('farm_name')->get();

        $filename = 'data-lahan-' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'Nama Lahan', 'Wilayah', 'Jenis Komoditas', 'Kelembapan Tanah (%)',
            'pH Tanah', 'Suhu Udara (C)', 'Curah Hujan (mm)', 'Kelembapan Udara (%)',
            'NDVI Index', 'Intensitas Sinar Matahari (jam)', 'Penggunaan Pestisida (ml)',
            'Durasi Masa Tanam (hari)', 'Cluster', 'Label Cluster', 'Estimasi Yield (kg/ha)',
        ];

        $callback = function () use ($farms, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($farms as $farm) {
                fputcsv($file, [
                    $farm->farm_name, $farm->region, $farm->crop_type,
                    $farm->soil_moisture, $farm->soil_pH, $farm->temperature_C,
                    $farm->rainfall_mm, $farm->humidity, $farm->NDVI_index,
                    $farm->sunlight_hours, $farm->pesticide_usage_ml, $farm->total_days,
                    $farm->cluster, $farm->cluster_label, $farm->predicted_yield,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        return view('farms.create');
    }

    public function aboutModel()
    {
        return view('farms.about-model');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_name'           => 'required|string|max:255',
            'region'               => 'required|string',
            'crop_type'            => 'required|string',
            'soil_moisture'        => 'required|numeric|min:0|max:100',
            'soil_pH'              => 'required|numeric|min:0|max:14',
            'temperature_C'        => 'required|numeric|min:-10|max:60',
            'rainfall_mm'          => 'required|numeric|min:0',
            'humidity'             => 'required|numeric|min:0|max:100',
            'NDVI_index'           => 'required|numeric|min:0|max:1',
            'sunlight_hours'       => 'required|numeric|min:0|max:24',
            'pesticide_usage_ml'   => 'required|numeric|min:0',
            'total_days'           => 'required|integer|min:1',
        ]);

        // Kirim ke FastAPI
        try {
            $response = Http::timeout(10)->post(env('FASTAPI_URL') . '/predict', [
                'soil_moisture'      => (float) $validated['soil_moisture'],
                'soil_pH'            => (float) $validated['soil_pH'],
                'temperature_C'      => (float) $validated['temperature_C'],
                'rainfall_mm'        => (float) $validated['rainfall_mm'],
                'humidity'           => (float) $validated['humidity'],
                'NDVI_index'         => (float) $validated['NDVI_index'],
                'sunlight_hours'     => (float) $validated['sunlight_hours'],
                'pesticide_usage_ml' => (float) $validated['pesticide_usage_ml'],
                'total_days'         => (int) $validated['total_days'],
            ]);

            if ($response->ok()) {
                $result = $response->json();
                $validated['cluster']          = $result['cluster'];
                $validated['cluster_label']    = $result['cluster_label'];
                $validated['predicted_yield']  = $result['predicted_yield_kg_per_ha'];
            }
        } catch (\Exception $e) {
            // Kalau FastAPI mati, tetap simpan tanpa prediksi
        }

        Farm::create($validated);
        return redirect()->route('farms.index')
            ->with('success', 'Data lahan berhasil ditambahkan!');
    }

    public function show(Farm $farm)
    {
        $recommendations = RecommendationService::generate($farm);
        return view('farms.show', compact('farm', 'recommendations'));
    }

    public function edit(Farm $farm)
    {
        return view('farms.edit', compact('farm'));
    }

    public function update(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'farm_name'           => 'required|string|max:255',
            'region'               => 'required|string',
            'crop_type'            => 'required|string',
            'soil_moisture'        => 'required|numeric|min:0|max:100',
            'soil_pH'              => 'required|numeric|min:0|max:14',
            'temperature_C'        => 'required|numeric|min:-10|max:60',
            'rainfall_mm'          => 'required|numeric|min:0',
            'humidity'             => 'required|numeric|min:0|max:100',
            'NDVI_index'           => 'required|numeric|min:0|max:1',
            'sunlight_hours'       => 'required|numeric|min:0|max:24',
            'pesticide_usage_ml'   => 'required|numeric|min:0',
            'total_days'           => 'required|integer|min:1',
        ]);

        try {
            $response = Http::timeout(10)->post(env('FASTAPI_URL') . '/predict', [
                'soil_moisture'      => (float) $validated['soil_moisture'],
                'soil_pH'            => (float) $validated['soil_pH'],
                'temperature_C'      => (float) $validated['temperature_C'],
                'rainfall_mm'        => (float) $validated['rainfall_mm'],
                'humidity'           => (float) $validated['humidity'],
                'NDVI_index'         => (float) $validated['NDVI_index'],
                'sunlight_hours'     => (float) $validated['sunlight_hours'],
                'pesticide_usage_ml' => (float) $validated['pesticide_usage_ml'],
                'total_days'         => (int) $validated['total_days'],
            ]);

            if ($response->ok()) {
                $result = $response->json();
                $validated['cluster']          = $result['cluster'];
                $validated['cluster_label']    = $result['cluster_label'];
                $validated['predicted_yield']  = $result['predicted_yield_kg_per_ha'];
            }
        } catch (\Exception $e) {}

        $farm->update($validated);
        return redirect()->route('farms.index')
            ->with('success', 'Data lahan berhasil diperbarui!');
    }

    public function destroy(Farm $farm)
    {
        $farm->delete();
        return redirect()->route('farms.index')
            ->with('success', 'Data lahan berhasil dihapus!');
    }

    public function trashed()
    {
        $farms = Farm::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(15);
        return view('farms.trashed', compact('farms'));
    }

    public function restore($id)
    {
        $farm = Farm::onlyTrashed()->findOrFail($id);
        $farm->restore();
        return redirect()->route('farms.trashed')
            ->with('success', 'Data lahan berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $farm = Farm::onlyTrashed()->findOrFail($id);
        $farm->forceDelete();
        return redirect()->route('farms.trashed')
            ->with('success', 'Data lahan berhasil dihapus permanen!');
    }

    public function compareForm()
    {
        $farms = Farm::orderBy('name')->get();
        return view('farms.compare-form', compact('farms'));
    }

    public function compareResult(Request $request)
    {
        $request->validate([
            'farm_ids'   => 'required|array|min:2|max:3',
            'farm_ids.*' => 'exists:farms,id',
        ]);

        $farms = Farm::whereIn('id', $request->farm_ids)->get();

        return view('farms.compare-result', compact('farms'));
    }
}
