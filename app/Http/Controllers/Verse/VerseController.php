<?php

namespace App\Http\Controllers\Verse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class VerseController extends Controller
{
    public function verseOfTheDay(Request $request)
    {
        $userId = $request->user_id;

        $cacheKey = "verse_of_day_{$userId}_" . now()->toDateString();

        $verse = Cache::remember($cacheKey, 86400, function () use ($userId) {

            $shownAyahs = DB::table('saved_verses')
                ->where('user_id', $userId)
                ->pluck('ayah_global_number')
                ->toArray();

            $randomAyah = $this->generateUniqueAyah($shownAyahs);

            return $this->fetchVerseWithRetry($randomAyah);
        });

        return response()->json([
            'status' => true,
            'data'   => $verse
        ]);
    }
    private function generateUniqueAyah(array $excluded)
    {
        $totalAyahs = 6236;

        do {
            $ayah = rand(1, $totalAyahs);
        } while (in_array($ayah, $excluded));

        return $ayah;
    }
    private function fetchVerseWithRetry($ayahNumber)
    {
        try {

            $response = Http::timeout(5)
                ->retry(3, 200)
                ->get("https://api.alquran.cloud/v1/ayah/{$ayahNumber}/editions/quran-uthmani,en.sahih");

            if ($response->successful()) {

                $data = $response->json()['data'];

                return [
                    'ayah_global_number' => $ayahNumber,
                    'arabic'             => $data[0]['text'],
                    'translation'        => $data[1]['text'],
                    'surah'              => $data[0]['surah']['englishName'],
                    'ayah_number'        => $data[0]['numberInSurah'],
                    'reference'          => $data[0]['surah']['englishName'] . ' ' . $data[0]['numberInSurah'],
                ];
            }
        } catch (\Exception $e) {
        }

        return $this->loadFromLocalJson($ayahNumber);
    }
    private function loadFromLocalJson($ayahNumber)
    {
        static $quran = null;

        if ($quran === null) {

            $file = storage_path('app/quran.json');

            if (!file_exists($file)) {
                throw new \Exception('quran.json not found');
            }

            $quran = json_decode(file_get_contents($file), true);

            if (!is_array($quran) || count($quran) !== 6236) {
                throw new \Exception('Invalid quran.json structure');
            }
        }

        if (!isset($quran[$ayahNumber])) {
            throw new \Exception("Ayah {$ayahNumber} not found");
        }

        $verse = $quran[$ayahNumber];

        return [
            'ayah_global_number' => $ayahNumber,
            'arabic'             => $verse['arabic'],
            'translation'        => $verse['translation'],
            'surah'              => $verse['surah'],
            'ayah_number'        => $verse['ayah_number'],
            'reference'          => $verse['surah'] . ' ' . $verse['ayah_number'],
        ];
    }

    public function saveVerse(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'ayah_global_number' => 'required'
        ]);

        DB::table('saved_verses')->insertOrIgnore([
            'user_id' => $request->user_id,
            'ayah_global_number' => $request->ayah_global_number,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = [
            'status'     => true,
            'message'    => 'Verse Saved Successfully',

        ];
        return response()->json($response, 200);
    }
    public function savedVerses(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $verses = DB::table('saved_verses')
            ->where('user_id', $request->user_id)
            ->orderByDesc('id')
            ->paginate(10);

        return response()->json($verses, 200);
    }
    public function removeSavedVerse(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'ayah_global_number' => 'required'
        ]);

        DB::table('saved_verses')
            ->where('user_id', $request->user_id)
            ->where('ayah_global_number', $request->ayah_global_number)
            ->delete();

        $response = [
            'status'     => true,
            'message'    => 'Verse Removed Successfully',

        ];
        return response()->json($response, 200);
    }
}
