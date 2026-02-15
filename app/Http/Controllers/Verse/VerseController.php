<?php

namespace App\Http\Controllers\Verse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VerseController extends Controller
{
    public function verseOfTheDay(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $userId = $request->user_id;

        $cacheKey = "verse_of_day_{$userId}_" . now()->toDateString();

        $verse = Cache::remember($cacheKey, 86400, function () use ($userId) {

            $shownAyahs = DB::table('saved_verses')
                ->where('user_id', $userId)
                ->pluck('ayah_global_number')
                ->toArray();

            $randomAyah = $this->generateUniqueAyah($shownAyahs);

            return $this->loadFromLocalJson($randomAyah);
        });

        return response()->json([
            'status' => true,
            'data'   => $verse
        ], 200);
    }

    private function generateUniqueAyah(array $excluded)
    {
        $totalAyahs = 6236;

        if (count($excluded) >= $totalAyahs) {
            return rand(1, $totalAyahs);
        }

        do {
            $ayah = rand(1, $totalAyahs);
        } while (in_array($ayah, $excluded));

        return $ayah;
    }

    private function loadFromLocalJson($ayahNumber)
    {
        static $quran = null;

        if ($quran === null) {

            $file = storage_path('app/quran.json');

            if (!file_exists($file)) {
                return [
                    'error' => 'Quran data not available'
                ];
            }

            $quran = json_decode(file_get_contents($file), true);

            if (!is_array($quran) || count($quran) !== 6236) {
                return [
                    'error' => 'Invalid Quran data'
                ];
            }
        }

        if (!isset($quran[$ayahNumber])) {
            return [
                'error' => 'Verse not found'
            ];
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

        return response()->json([
            'status'  => true,
            'message' => 'Verse Saved Successfully',
        ], 200);
    }

    public function savedVerses(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $paginator = DB::table('saved_verses')
            ->where('user_id', $request->user_id)
            ->orderByDesc('id')
            ->paginate(10);

        if ($paginator->total() === 0) {
            return response()->json([
                'status'  => false,
                'message' => 'You do not have any saved verses.'
            ], 200);
        }

        $transformed = [];

        foreach ($paginator->items() as $item) {
            $transformed[] = $this->loadFromLocalJson($item->ayah_global_number);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'data'         => $transformed,
            ]
        ], 200);
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

        return response()->json([
            'status'  => true,
            'message' => 'Verse Removed Successfully',
        ], 200);
    }
}
