<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiInsightService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl  = 'https://generativelanguage.googleapis.com/v1beta/models';
    private int    $cacheTtl = 60 * 60 * 5; // 5 jam

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', '');
        $this->model  = env('GEMINI_MODEL', 'gemini-3.1-flash-lite');
    }

    // ── Public API ────────────────────────────────────────────────────────

    public function getInsights(int $districtId, string $districtName, array $stats): array
    {
        $cacheKey = 'gemini_district_' . $districtId . '_' . floor(now()->timestamp / $this->cacheTtl);
        return Cache::remember($cacheKey, $this->cacheTtl, fn () => $this->callGemini($districtName, $stats));
    }

    public function refreshInsights(int $districtId, string $districtName, array $stats): array
    {
        $cacheKey = 'gemini_district_' . $districtId . '_' . floor(now()->timestamp / $this->cacheTtl);
        Cache::forget($cacheKey);
        return $this->getInsights($districtId, $districtName, $stats);
    }

    public function getCacheInfo(int $districtId): array
    {
        $cacheKey  = 'gemini_district_' . $districtId . '_' . floor(now()->timestamp / $this->cacheTtl);
        $nextReset = Carbon::createFromTimestamp(
            (floor(now()->timestamp / $this->cacheTtl) + 1) * $this->cacheTtl
        );
        return [
            'cached'     => Cache::has($cacheKey),
            'next_reset' => $nextReset->translatedFormat('j M Y, H:i'),
        ];
    }

    public function testConnection(): array
    {
        if (empty($this->apiKey)) {
            return ['ok' => false, 'error' => 'API key kosong — cek GEMINI_API_KEY di .env lalu php artisan config:clear'];
        }

        try {
            $url      = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";
            $response = Http::timeout(15)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents'         => [['parts' => [['text' => 'Balas dengan kata: OK']]]],
                    'generationConfig' => ['maxOutputTokens' => 10],
                ]);

            return [
                'ok'         => $response->successful(),
                'status'     => $response->status(),
                'model'      => $this->model,
                'key_prefix' => substr($this->apiKey, 0, 8) . '...',
                'response'   => $response->successful()
                    ? $response->json('candidates.0.content.parts.0.text')
                    : $response->body(),
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    public function listAvailableModels(): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'API key kosong'];
        }

        try {
            $response = Http::get("{$this->baseUrl}?key={$this->apiKey}");
            $models   = collect($response->json('models', []))
                ->filter(fn ($m) => in_array('generateContent', $m['supportedGenerationMethods'] ?? []))
                ->map(fn ($m) => $m['name'])
                ->values()
                ->toArray();

            return ['models' => $models, 'current_model' => $this->model];
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ── Private ───────────────────────────────────────────────────────────

    /**
     * Coba model satu per satu sampai berhasil (fallback chain untuk 429/404).
     * foreach TIDAK di dalam try-catch — masing-masing request punya try sendiri.
     */
    private function callGemini(string $districtName, array $stats): array
    {
        if (empty($this->apiKey)) {
            return $this->fallbackInsights($districtName, $stats);
        }

        $modelsToTry = array_values(array_unique([
            $this->model,
            'gemini-2.0-flash-lite',
            'gemini-1.5-flash',
            'gemini-1.5-pro',
        ]));

        $prompt = $this->buildPrompt($districtName, $stats);

        foreach ($modelsToTry as $model) {
            $result = $this->tryModel($model, $prompt, $districtName, $stats);

            // null = model gagal (429/404) → coba berikutnya
            // array = berhasil atau fallback sudah diputuskan
            if ($result !== null) {
                return $result;
            }
        }

        // Semua model gagal
        Log::error('All Gemini models failed');
        return $this->fallbackInsights($districtName, $stats);
    }

    /**
     * Coba satu model. Return null jika perlu coba model lain (429/404).
     */
    private function tryModel(string $model, string $prompt, string $districtName, array $stats): ?array
    {
        try {
            $url = "{$this->baseUrl}/{$model}:generateContent?key={$this->apiKey}";

            Log::info('Gemini trying model', ['model' => $model]);

            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents'         => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature'      => 0.4,
                        'maxOutputTokens'  => 800,
                        'responseMimeType' => 'application/json',
                    ],
                ]);

            // 429 quota habis / 404 model tidak ada → return null → coba model berikutnya
            if ($response->status() === 429 || $response->status() === 404) {
                Log::warning('Gemini model skipped', ['model' => $model, 'status' => $response->status()]);
                return null;
            }

            if ($response->failed()) {
                Log::error('Gemini request failed', ['model' => $model, 'status' => $response->status()]);
                return $this->fallbackInsights($districtName, $stats);
            }

            $text   = $response->json('candidates.0.content.parts.0.text', '');
            $parsed = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE || empty($parsed['insights'])) {
                Log::warning('Gemini bad JSON', ['model' => $model, 'text' => substr($text, 0, 200)]);
                return $this->fallbackInsights($districtName, $stats);
            }

            Log::info('Gemini success', ['model' => $model]);

            return array_merge($parsed, [
                'generated_at' => now()->translatedFormat('j M Y, H:i'),
                'source'       => 'gemini',
            ]);

        } catch (\Throwable $e) {
            Log::error('Gemini exception', ['model' => $model, 'error' => $e->getMessage()]);
            return $this->fallbackInsights($districtName, $stats);
        }
    }

    private function buildPrompt(string $districtName, array $stats): string
    {
        $kategoriList = collect($stats['kategori'] ?? [])
            ->map(fn ($k) => "  - {$k['nama']}: {$k['total']} laporan, {$k['selesai']} selesai, {$k['terlambat']} terlambat, completion {$k['completion']}%")
            ->implode("\n");

        $dinasList = collect($stats['dinas_terlambat'] ?? [])
            ->map(fn ($d) => "  - {$d['nama']}: {$d['pct_terlambat']}% terlambat ({$d['terlambat']} dari {$d['total']})")
            ->implode("\n");

        $trendList = collect($stats['trend'] ?? [])
            ->map(fn ($t) => "  - {$t['bulan_short']}: {$t['total']} laporan, {$t['selesai']} selesai, {$t['terlambat']} terlambat")
            ->implode("\n");

        return <<<PROMPT
Kamu adalah analis pemerintah daerah Kabupaten Badung, Bali.
Analisis data laporan pengaduan masyarakat Kecamatan {$districtName} berikut.

STATISTIK UMUM:
- Total: {$stats['total']}, Selesai: {$stats['selesai']} ({$stats['completion_rate']}%)
- Diproses: {$stats['sedang_diproses']}, Terlambat: {$stats['terlambat']}
- Rata-rata waktu: {$stats['avg_hari']} hari, Kepuasan: {$stats['kepuasan']}%

KATEGORI:
{$kategoriList}

DINAS TERLAMBAT:
{$dinasList}

TREND:
{$trendList}

Berikan TEPAT 3 insight dalam format JSON:
{
  "insights": [
    {
      "type": "warning|info|success",
      "title": "Judul singkat maks 60 karakter",
      "summary": "Ringkasan 1-2 kalimat maks 150 karakter",
      "detail": "Detail + rekomendasi spesifik maks 300 karakter",
      "data_point": "Angka kunci pendukung insight"
    }
  ],
  "rekomendasi_utama": "Satu rekomendasi terpenting maks 200 karakter"
}

Gunakan Bahasa Indonesia formal. Fokus pada hal ACTIONABLE untuk Kecamatan {$districtName}.
PROMPT;
    }

    private function fallbackInsights(string $districtName, array $stats): array
    {
        $total      = $stats['total'] ?? 0;
        $terlambat  = $stats['terlambat'] ?? 0;
        $completion = $stats['completion_rate'] ?? 0;
        $pctLambat  = $total > 0 ? round(($terlambat / $total) * 100, 1) : 0;
        $topKat     = $stats['kategori'][0] ?? null;

        $insights = [];

        $insights[] = $completion >= 90 ? [
            'type'       => 'success',
            'title'      => "Performa Kecamatan {$districtName} Sangat Baik",
            'summary'    => "Completion rate {$completion}% sudah di atas target 90%.",
            'detail'     => "Pertahankan momentum ini dengan memastikan kecepatan respons tetap konsisten di semua OPD.",
            'data_point' => "{$completion}% completion rate",
        ] : [
            'type'       => 'warning',
            'title'      => "Completion Rate Perlu Ditingkatkan",
            'summary'    => "Completion rate {$completion}% masih di bawah target 90%.",
            'detail'     => "Koordinasikan dengan OPD terkait untuk mempercepat penanganan laporan yang masih pending dan mengurangi backlog.",
            'data_point' => "{$completion}% dari target 90%",
        ];

        $insights[] = $pctLambat > 10 ? [
            'type'       => 'warning',
            'title'      => "{$pctLambat}% Laporan Melewati Batas SLA",
            'summary'    => "{$terlambat} dari {$total} laporan melampaui batas waktu penanganan.",
            'detail'     => "Tinjau kapasitas petugas dan alur eskalasi. Pertimbangkan penambahan tenaga atau penyederhanaan prosedur.",
            'data_point' => "{$terlambat} laporan terlambat",
        ] : [
            'type'       => 'success',
            'title'      => "Tingkat Keterlambatan Terkendali",
            'summary'    => "Hanya {$pctLambat}% laporan yang melampaui SLA.",
            'detail'     => "Fokus selanjutnya adalah mempertahankan konsistensi dan meningkatkan kepuasan masyarakat.",
            'data_point' => "{$pctLambat}% keterlambatan",
        ];

        if ($topKat) {
            $pctKat     = $total > 0 ? round(($topKat['total'] / $total) * 100, 1) : 0;
            $insights[] = [
                'type'       => 'info',
                'title'      => "{$topKat['nama']} Mendominasi {$pctKat}% Laporan",
                'summary'    => "Kategori {$topKat['nama']} paling banyak dilaporkan dengan {$topKat['total']} laporan.",
                'detail'     => "Tingginya laporan ini mengindikasikan kebutuhan peningkatan di area ini. Rekomendasikan alokasi anggaran prioritas.",
                'data_point' => "{$topKat['total']} laporan ({$pctKat}%)",
            ];
        }

        return [
            'insights'          => $insights,
            'rekomendasi_utama' => "Prioritaskan penanganan {$terlambat} laporan terlambat dan tingkatkan completion rate dari {$completion}% menuju target 90%.",
            'generated_at'      => now()->translatedFormat('j M Y, H:i'),
            'source'            => 'fallback',
        ];
    }
}