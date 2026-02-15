<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class BuildQuran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:build-quran';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Downloading Quran...");

        $arabicResponse = Http::timeout(60)
            ->get("https://api.alquran.cloud/v1/quran/quran-uthmani");

        if (!$arabicResponse->successful()) {
            $this->error("Failed to download Arabic Quran");
            return;
        }

        $englishResponse = Http::timeout(60)
            ->get("https://api.alquran.cloud/v1/quran/en.sahih");

        if (!$englishResponse->successful()) {
            $this->error("Failed to download English Quran");
            return;
        }

        $arabic = $arabicResponse->json()['data']['surahs'];
        $english = $englishResponse->json()['data']['surahs'];

        $result = [];
        $globalIndex = 1;

        foreach ($arabic as $sIndex => $surah) {
            foreach ($surah['ayahs'] as $aIndex => $ayah) {

                $result[$globalIndex] = [
                    'ayah_global_number' => $globalIndex,
                    'surah_number'       => $surah['number'],
                    'surah'              => $surah['englishName'],
                    'ayah_number'        => $ayah['numberInSurah'],
                    'arabic'             => $ayah['text'],
                    'translation'        => $english[$sIndex]['ayahs'][$aIndex]['text'],
                ];

                $globalIndex++;
            }
        }

        file_put_contents(
            storage_path('app/quran.json'),
            json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        $this->info("Built successfully");
    }
}
