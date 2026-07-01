<?php

namespace App\Services;

use App\Models\Farm;

class RecommendationService
{
    /**
     * Hasilkan daftar rekomendasi untuk satu lahan.
     * Return array of ['level' => 'danger'|'warning'|'info'|'success', 'message' => string]
     */
    public static function generate(Farm $farm): array
    {
        $recs = [];

        // 1. Rekomendasi berdasarkan kelembaban tanah
        if ($farm->soil_moisture < 20) {
            $recs[] = [
                'level' => 'danger',
                'message' => "Kelembaban tanah sangat rendah ({$farm->soil_moisture}%). Disarankan segera lakukan irigasi tambahan.",
            ];
        } elseif ($farm->soil_moisture < 35) {
            $recs[] = [
                'level' => 'warning',
                'message' => "Kelembaban tanah cenderung rendah ({$farm->soil_moisture}%). Pertimbangkan penyiraman dalam 1-2 hari ke depan.",
            ];
        } elseif ($farm->soil_moisture > 80) {
            $recs[] = [
                'level' => 'warning',
                'message' => "Kelembaban tanah sangat tinggi ({$farm->soil_moisture}%). Periksa drainase untuk mencegah akar membusuk.",
            ];
        }

        // 2. Rekomendasi berdasarkan pH tanah
        if ($farm->soil_pH < 5.5) {
            $recs[] = [
                'level' => 'warning',
                'message' => "pH tanah cenderung asam ({$farm->soil_pH}). Pertimbangkan pengapuran (liming) untuk menaikkan pH mendekati netral.",
            ];
        } elseif ($farm->soil_pH > 7.5) {
            $recs[] = [
                'level' => 'warning',
                'message' => "pH tanah cenderung basa ({$farm->soil_pH}). Pertimbangkan penambahan bahan organik untuk menurunkan pH.",
            ];
        }

        // 3. Rekomendasi berdasarkan NDVI (indeks kesehatan vegetasi)
        if (!is_null($farm->NDVI_index)) {
            if ($farm->NDVI_index < 0.3) {
                $recs[] = [
                    'level' => 'danger',
                    'message' => "Indeks vegetasi (NDVI) rendah ({$farm->NDVI_index}), menandakan tanaman kurang sehat. Cek kemungkinan kekurangan nutrisi atau hama.",
                ];
            } elseif ($farm->NDVI_index > 0.6) {
                $recs[] = [
                    'level' => 'success',
                    'message' => "Indeks vegetasi (NDVI) baik ({$farm->NDVI_index}), tanaman tumbuh sehat. Pertahankan pola perawatan saat ini.",
                ];
            }
        }

        // 4. Rekomendasi berdasarkan penggunaan pestisida
        if ($farm->pesticide_usage_ml > 500) {
            $recs[] = [
                'level' => 'warning',
                'message' => "Penggunaan pestisida cukup tinggi ({$farm->pesticide_usage_ml} ml). Evaluasi dosis untuk efisiensi biaya dan keberlanjutan lingkungan.",
            ];
        }

        // 5. Rekomendasi berdasarkan cluster hasil K-Means
        if (!is_null($farm->cluster)) {
            $clusterMessage = match ((int) $farm->cluster) {
                0 => "Lahan masuk kategori produktivitas rendah. Prioritaskan perbaikan kelembaban dan nutrisi tanah sebelum musim tanam berikutnya.",
                1 => "Lahan masuk kategori produktivitas sedang. Ada ruang optimasi pada irigasi atau pemupukan untuk naik ke kategori tinggi.",
                2 => "Lahan masuk kategori produktivitas tinggi. Pertahankan praktik perawatan saat ini sebagai acuan untuk lahan lain.",
                default => null,
            };
            if ($clusterMessage) {
                $level = match ((int) $farm->cluster) {
                    0 => 'danger',
                    1 => 'warning',
                    2 => 'success',
                    default => 'info',
                };
                $recs[] = ['level' => $level, 'message' => $clusterMessage];
            }
        }

        // 6. Rekomendasi berdasarkan prediksi yield vs rata-rata historis
        if (!is_null($farm->predicted_yield)) {
            $avgYield = Farm::whereNotNull('predicted_yield')->avg('predicted_yield');
            if ($avgYield && $farm->predicted_yield < $avgYield * 0.8) {
                $recs[] = [
                    'level' => 'warning',
                    'message' => "Prediksi yield lahan ini (" . round($farm->predicted_yield, 2) . ") berada di bawah rata-rata seluruh lahan (" . round($avgYield, 2) . "). Pertimbangkan evaluasi faktor tanah dan iklim.",
                ];
            }
        }

        // Fallback kalau tidak ada kondisi bermasalah sama sekali
        if (empty($recs)) {
            $recs[] = [
                'level' => 'success',
                'message' => "Seluruh parameter lahan dalam rentang normal. Tidak ada tindakan khusus yang diperlukan saat ini.",
            ];
        }

        return $recs;
    }
}
