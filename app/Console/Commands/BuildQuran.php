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
        $result = [];

        for ($i = 1; $i <= 6236; $i++) {

            $response = Http::get(
                "https://api.alquran.cloud/v1/ayah/{$i}/editions/quran-uthmani,en.sahih"
            );

            $data = $response->json()['data'];

            $result[$i] = [
                'ayah_global_number' => $i,
                'surah_number'       => $data[0]['surah']['number'],
                'surah'              => $data[0]['surah']['englishName'],
                'ayah_number'        => $data[0]['numberInSurah'],
                'arabic'             => $data[0]['text'],
                'translation'        => $data[1]['text'],
            ];
        }

        file_put_contents(
            storage_path('app/quran.json'),
            json_encode($result, JSON_UNESCAPED_UNICODE)
        );

        $this->info('Built successfully');
    }
}
