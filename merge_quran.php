<?php

$surahPath = __DIR__ . '/storage/app/quran/surah/';
$translationPath = __DIR__ . '/storage/app/quran/translation/';

$result = [];
$globalIndex = 1;
$totalAyahs = 0;

for ($surahNumber = 1; $surahNumber <= 114; $surahNumber++) {

    $surahFile = $surahPath . 'surah_' . $surahNumber . '.json';
    $translationFile = $translationPath . 'en_translation_' . $surahNumber . '.json';

    if (!file_exists($surahFile)) {
        die("Missing Arabic file for Surah {$surahNumber}\n");
    }

    if (!file_exists($translationFile)) {
        die("Missing Translation file for Surah {$surahNumber}\n");
    }

    $surahData = json_decode(file_get_contents($surahFile), true);
    $translationData = json_decode(file_get_contents($translationFile), true);

    $surahName = $surahData['name'];

    $arabicVerses = $surahData['verse'];
    $translationVerses = $translationData['verse'];

    if (!is_array($arabicVerses) || !is_array($translationVerses)) {
        die("Invalid structure in Surah {$surahNumber}\n");
    }

    foreach ($arabicVerses as $key => $arabicText) {

        if (!isset($translationVerses[$key])) {
            die("Translation mismatch at Surah {$surahNumber}, {$key}\n");
        }

        $ayahNumber = (int) str_replace('verse_', '', $key);

        $result[$globalIndex] = [
            'ayah_global_number' => $globalIndex,
            'surah_number'       => $surahNumber,
            'surah'              => $surahName,
            'ayah_number'        => $ayahNumber,
            'arabic'             => $arabicText,
            'translation'        => $translationVerses[$key],
        ];

        $globalIndex++;
        $totalAyahs++;
    }
}

file_put_contents(
    __DIR__ . '/storage/app/quran.json',
    json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);

echo "Merge complete.\n";
echo "Total Ayahs: {$totalAyahs}\n";
