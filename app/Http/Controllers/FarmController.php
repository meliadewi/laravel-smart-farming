<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FarmController extends Controller
{
    public function index()
{
    $farms = Farm::latest()->paginate(10);
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

    return view('farms.index', compact('farms', 'total', 'clusters', 'avgYield', 'yieldByCrop'));
}

    public function create()
    {
        return view('farms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_name'         => 'required|string|max:255',
            'region'            => 'required|string',
            'crop_type'         => 'required|string',
            'soil_moisture'     => 'required|numeric|min:0|max:100',
            'soil_pH'           => 'required|numeric|min:0|max:14',
            'temperature_C'     => 'required|numeric|min:-10|max:60',
            'rainfall_mm'       => 'required|numeric|min:0',
            'humidity'          => 'required|numeric|min:0|max:100',
            'NDVI_index'        => 'required|numeric|min:0|max:1',
            'sunlight_hours'    => 'required|numeric|min:0|max:24',
            'pesticide_usage_ml'=> 'required|numeric|min:0',
            'total_days'        => 'required|integer|min:1',
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
                $validated['cluster']         = $result['cluster'];
                $validated['cluster_label']   = $result['cluster_label'];
                $validated['predicted_yield'] = $result['predicted_yield_kg_per_ha'];
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
        return view('farms.show', compact('farm'));
    }

    public function edit(Farm $farm)
    {
        return view('farms.edit', compact('farm'));
    }

    public function update(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'farm_name'         => 'required|string|max:255',
            'region'            => 'required|string',
            'crop_type'         => 'required|string',
            'soil_moisture'     => 'required|numeric|min:0|max:100',
            'soil_pH'           => 'required|numeric|min:0|max:14',
            'temperature_C'     => 'required|numeric|min:-10|max:60',
            'rainfall_mm'       => 'required|numeric|min:0',
            'humidity'          => 'required|numeric|min:0|max:100',
            'NDVI_index'        => 'required|numeric|min:0|max:1',
            'sunlight_hours'    => 'required|numeric|min:0|max:24',
            'pesticide_usage_ml'=> 'required|numeric|min:0',
            'total_days'        => 'required|integer|min:1',
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
                $validated['cluster']         = $result['cluster'];
                $validated['cluster_label']   = $result['cluster_label'];
                $validated['predicted_yield'] = $result['predicted_yield_kg_per_ha'];
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
}
