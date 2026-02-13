<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RamadanWeek;
use App\Models\RamadanTopic;
use App\Models\RamadanQuestion;
use App\Models\RamadanOption;

class RamadanQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($week = 1; $week <= 5; $week++) {
            RamadanWeek::create([
                'week_number' => $week
            ]);
        }
        $topicsData = $this->getTopicsData();
        foreach ($topicsData as $data) {

            $topic = RamadanTopic::create([
                'week_id' => $data['week_id'],
                'day_number' => $data['day_number'],
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'max_points' => 100,
                'total_questions' => 5,
            ]);


            foreach ($data['questions'] as  $qIndex => $questionData) {

                $question =  RamadanQuestion::create([
                    'topic_id' => $topic->id,
                    'question_number' => $qIndex + 1,
                    'question' => $questionData['question'],
                    'points' => 20
                ]);

                foreach ($questionData['options'] as $index => $optionText) {
                    RamadanOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct' => $index === $questionData['correct_index']
                    ]);
                }
            }
        }
    }

    private function getTopicsData()
    {
        return [
            [
                'week_id' => 1,
                'day_number' => 1,
                'title' => 'Moon Sighting & Ramadan Start',
                'subtitle' => 'Lunar Calendar Basics',
                'questions' => [
                    [
                        'question' => 'When does Ramadan begin?',
                        'options' => [
                            'When moon is sighted',
                            'On fixed Gregorian date',
                            'After 31 days',
                            'After Jumuah'
                        ],
                        'correct_index' => 0
                    ],
                    [
                        'question' => 'Which month comes before Ramadan?',
                        'options' => [
                            'Rajab',
                            'Sha’ban',
                            'Muharram',
                            'Dhul Hijjah'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Islamic calendar is based on?',
                        'options' => [
                            'Solar cycle',
                            'Lunar cycle',
                            'Gregorian system',
                            'Roman calendar'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'If moon not sighted on 29th Sha’ban?',
                        'options' => [
                            'Start fasting anyway',
                            'Complete 30 days Sha’ban',
                            'Eid declared',
                            'Fast optional'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Hilal refers to?',
                        'options' => [
                            'Full moon',
                            'Crescent moon',
                            'Star',
                            'Planet'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 1,
                'day_number' => 2,
                'title' => 'Obligation of Fasting (Sawm)',
                'subtitle' => 'Fasting Rules Overview',
                'questions' => [
                    [
                        'question' => 'In which Hijri year was fasting made obligatory?',
                        'options' => [
                            '1 AH',
                            '2 AH',
                            '5 AH',
                            '8 AH'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Which of the following breaks the fast intentionally?',
                        'options' => [
                            'Forgetting and eating',
                            'Accidentally drinking water',
                            'Intentional eating or drinking',
                            'Sleeping during the day'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The intention (niyyah) for fasting must be made before?',
                        'options' => [
                            'Sunrise',
                            'Maghrib',
                            'Fajr',
                            'Dhuhr'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Who is exempt from fasting during Ramadan?',
                        'options' => [
                            'Healthy adult Muslim',
                            'Traveler and sick person',
                            'Wealthy person',
                            'Student'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'The pre-dawn meal before fasting is called?',
                        'options' => [
                            'Iftar',
                            'Suhoor',
                            'Zakat',
                            'Tahajjud'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 1,
                'day_number' => 3,
                'title' => 'Revelation of the Qur’an',
                'subtitle' => 'Beginning of Revelation',
                'questions' => [
                    [
                        'question' => 'In which month was the Qur’an revealed?',
                        'options' => [
                            'Muharram',
                            'Rajab',
                            'Ramadan',
                            'Rabi ul Awwal'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The first revelation came in which cave?',
                        'options' => [
                            'Cave Thawr',
                            'Cave Hira',
                            'Cave Safa',
                            'Cave Mina'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'What was the first word revealed to Prophet Muhammad (ﷺ)?',
                        'options' => [
                            'Pray',
                            'Believe',
                            'Read',
                            'Submit'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Laylatul Qadr is described as better than how many months?',
                        'options' => [
                            '100 months',
                            '500 months',
                            '1000 months',
                            '10,000 months'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Which Surah specifically mentions Laylatul Qadr?',
                        'options' => [
                            'Surah Al-Fajr',
                            'Surah Al-Qadr',
                            'Surah Al-Baqarah',
                            'Surah Al-Ikhlas'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 1,
                'day_number' => 4,
                'title' => 'Battle of Badr',
                'subtitle' => 'Historic Islamic Victory',
                'questions' => [
                    [
                        'question' => 'In which Hijri year did the Battle of Badr take place?',
                        'options' => [
                            '1 AH',
                            '2 AH',
                            '3 AH',
                            '5 AH'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Approximately how many Muslims participated in the Battle of Badr?',
                        'options' => [
                            '100',
                            '313',
                            '500',
                            '700'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'On which date of Ramadan did the Battle of Badr occur?',
                        'options' => [
                            '1st Ramadan',
                            '10th Ramadan',
                            '17th Ramadan',
                            '27th Ramadan'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Who led the Muslim army at the Battle of Badr?',
                        'options' => [
                            'Abu Bakr (RA)',
                            'Umar (RA)',
                            'Prophet Muhammad (ﷺ)',
                            'Ali (RA)'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Badr is located near which city?',
                        'options' => [
                            'Makkah',
                            'Madinah',
                            'Taif',
                            'Jerusalem'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 1,
                'day_number' => 5,
                'title' => 'Laylatul Qadr',
                'subtitle' => 'Night of Power',
                'questions' => [
                    [
                        'question' => 'Laylatul Qadr occurs during which part of Ramadan?',
                        'options' => [
                            'First 10 days',
                            'Middle 10 days',
                            'Last 10 days',
                            'Day of Eid'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Laylatul Qadr is most commonly sought on which nights?',
                        'options' => [
                            'Even nights',
                            'Odd nights',
                            'First night only',
                            '15th night'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Laylatul Qadr is described as better than how many months?',
                        'options' => [
                            '100 months',
                            '500 months',
                            '1000 months',
                            '2000 months'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Which companion asked the Prophet (ﷺ) what to recite on Laylatul Qadr?',
                        'options' => [
                            'Aisha (RA)',
                            'Umar (RA)',
                            'Bilal (RA)',
                            'Abu Huraira (RA)'
                        ],
                        'correct_index' => 0
                    ],
                    [
                        'question' => 'Which special prayer is emphasized during the last ten nights?',
                        'options' => [
                            'Jumu’ah',
                            'Taraweeh',
                            'Tahajjud',
                            'Eid prayer'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],
            [
                'week_id' => 1,
                'day_number' => 6,
                'title' => 'Zakat and Charity',
                'subtitle' => 'Wealth Purification Principles',
                'questions' => [
                    [
                        'question' => 'Zakat is one of how many pillars of Islam?',
                        'options' => [
                            '3',
                            '4',
                            '5',
                            '6'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The standard rate of Zakat on savings is?',
                        'options' => [
                            '1%',
                            '2.5%',
                            '5%',
                            '10%'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Zakat becomes obligatory after wealth is held for how long?',
                        'options' => [
                            '1 month',
                            '6 months',
                            '1 lunar year',
                            '5 years'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Charity given at the end of Ramadan is called?',
                        'options' => [
                            'Sadaqah',
                            'Zakat al-Fitr',
                            'Khums',
                            'Fidyah'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Zakat cannot be given to which of the following?',
                        'options' => [
                            'Poor person',
                            'Needy person',
                            'Debtor in hardship',
                            'Wealthy person'
                        ],
                        'correct_index' => 3
                    ],
                ]
            ],
            [
                'week_id' => 1,
                'day_number' => 7,
                'title' => 'Eid al-Fitr',
                'subtitle' => 'Festival of Breaking Fast',
                'questions' => [
                    [
                        'question' => 'Eid al-Fitr marks the end of which month?',
                        'options' => [
                            'Muharram',
                            'Ramadan',
                            'Rajab',
                            'Dhul Hijjah'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Before going for Eid prayer, Muslims are required to give?',
                        'options' => [
                            'Zakat al-Mal',
                            'Zakat al-Fitr',
                            'Khums',
                            'Fidyah'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Eid prayer is performed at which time of the day?',
                        'options' => [
                            'Before Fajr',
                            'After Dhuhr',
                            'In the morning after sunrise',
                            'After Maghrib'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'It is Sunnah to eat what before going to Eid prayer?',
                        'options' => [
                            'Bread',
                            'Milk',
                            'Dates',
                            'Rice'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The Takbeer of Eid is recited until?',
                        'options' => [
                            'The prayer begins',
                            'The prayer ends',
                            'Sunset',
                            'Midnight'
                        ],
                        'correct_index' => 0
                    ],
                ]
            ],
            [
                'week_id' => 2,
                'day_number' => 8,
                'title' => 'Hijrah (Migration to Madinah)',
                'subtitle' => 'Migration to Madinah',
                'questions' => [
                    [
                        'question' => 'The Hijrah refers to the migration from?',
                        'options' => [
                            'Madinah to Makkah',
                            'Makkah to Madinah',
                            'Taif to Makkah',
                            'Jerusalem to Madinah'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Which companion accompanied the Prophet (ﷺ) during the Hijrah?',
                        'options' => [
                            'Umar (RA)',
                            'Ali (RA)',
                            'Abu Bakr (RA)',
                            'Uthman (RA)'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'In which cave did the Prophet (ﷺ) and Abu Bakr (RA) take shelter?',
                        'options' => [
                            'Cave Hira',
                            'Cave Thawr',
                            'Cave Safa',
                            'Cave Mina'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'In which year did the Hijrah take place (CE)?',
                        'options' => [
                            '610 CE',
                            '620 CE',
                            '622 CE',
                            '630 CE'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The Islamic calendar begins from which event?',
                        'options' => [
                            'Battle of Badr',
                            'First revelation',
                            'Hijrah',
                            'Conquest of Makkah'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],
            [
                'week_id' => 2,
                'day_number' => 9,
                'title' => 'Treaty of Hudaybiyyah',
                'subtitle' => 'Peace Agreement with Quraysh',
                'questions' => [
                    [
                        'question' => 'In which Hijri year was the Treaty of Hudaybiyyah signed?',
                        'options' => [
                            '2 AH',
                            '4 AH',
                            '6 AH',
                            '8 AH'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The Treaty of Hudaybiyyah was signed between Muslims and which tribe?',
                        'options' => [
                            'Banu Hashim',
                            'Quraysh',
                            'Ansar',
                            'Banu Thaqif'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'The treaty was agreed upon for how many years?',
                        'options' => [
                            '5 years',
                            '8 years',
                            '10 years',
                            '12 years'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Which companion initially found the terms difficult to accept?',
                        'options' => [
                            'Abu Bakr (RA)',
                            'Umar (RA)',
                            'Ali (RA)',
                            'Bilal (RA)'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'What was the major outcome of the Treaty of Hudaybiyyah?',
                        'options' => [
                            'Immediate war',
                            'Destruction of idols',
                            'A period of peace',
                            'Migration to Taif'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],
            [
                'week_id' => 2,
                'day_number' => 10,
                'title' => 'Conquest of Makkah',
                'subtitle' => 'Victory Over Makkah',
                'questions' => [
                    [
                        'question' => 'In which Hijri year did the Conquest of Makkah occur?',
                        'options' => [
                            '6 AH',
                            '7 AH',
                            '8 AH',
                            '9 AH'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The Conquest of Makkah took place during which month?',
                        'options' => [
                            'Rajab',
                            'Sha’ban',
                            'Ramadan',
                            'Dhul Hijjah'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'How did the Prophet (ﷺ) enter Makkah during the conquest?',
                        'options' => [
                            'Secretly at night',
                            'With a large army peacefully',
                            'Alone',
                            'With only a few companions'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'What did the Prophet (ﷺ) do to the idols inside the Kaaba?',
                        'options' => [
                            'Ignored them',
                            'Moved them outside',
                            'Destroyed them',
                            'Covered them'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'What did the Prophet (ﷺ) grant the people of Makkah after the conquest?',
                        'options' => [
                            'Punishment',
                            'Exile',
                            'General amnesty',
                            'Heavy tax'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],
            [
                'week_id' => 2,
                'day_number' => 11,
                'title' => 'Taraweeh Prayer',
                'subtitle' => 'Night Prayer in Ramadan',
                'questions' => [
                    [
                        'question' => 'Taraweeh prayer is performed during which month?',
                        'options' => [
                            'Muharram',
                            'Ramadan',
                            'Rajab',
                            'Dhul Hijjah'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Taraweeh is prayed after which obligatory prayer?',
                        'options' => [
                            'Maghrib',
                            'Isha',
                            'Dhuhr',
                            'Fajr'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Taraweeh prayer is classified as?',
                        'options' => [
                            'Fard (obligatory)',
                            'Wajib',
                            'Sunnah',
                            'Makruh'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Who organized the Taraweeh prayer in congregation during his caliphate?',
                        'options' => [
                            'Abu Bakr (RA)',
                            'Umar (RA)',
                            'Uthman (RA)',
                            'Ali (RA)'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'The number of rak’ahs in Taraweeh prayer can vary?',
                        'options' => [
                            'Yes',
                            'No',
                            'Only 2 rak’ahs allowed',
                            'Only 4 rak’ahs allowed'
                        ],
                        'correct_index' => 0
                    ],
                ]
            ],
            [
                'week_id' => 2,
                'day_number' => 12,
                'title' => 'I’tikaf (Spiritual Retreat)',
                'subtitle' => 'Spiritual Mosque Retreat',
                'questions' => [
                    [
                        'question' => 'I’tikaf is most commonly observed during which days of Ramadan?',
                        'options' => [
                            'First 10 days',
                            'Middle 10 days',
                            'Last 10 days',
                            'Only on the 27th night'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'I’tikaf is performed in which place?',
                        'options' => [
                            'Home',
                            'Marketplace',
                            'Mosque',
                            'Desert'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The main purpose of I’tikaf is?',
                        'options' => [
                            'Social gathering',
                            'Business planning',
                            'Devotion and worship',
                            'Travel'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'I’tikaf was regularly practiced by which Prophet?',
                        'options' => [
                            'Prophet Musa (AS)',
                            'Prophet Isa (AS)',
                            'Prophet Muhammad (ﷺ)',
                            'Prophet Ibrahim (AS)'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'A person in I’tikaf may leave the mosque for?',
                        'options' => [
                            'Shopping',
                            'Social visits',
                            'Necessary needs only',
                            'Work meetings'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],
            [
                'week_id' => 2,
                'day_number' => 13,
                'title' => 'Suhoor and Iftar',
                'subtitle' => 'Pre-Dawn and Breaking Fast',
                'questions' => [
                    [
                        'question' => 'Suhoor is the meal taken at what time?',
                        'options' => [
                            'After Maghrib',
                            'Before Fajr',
                            'After Dhuhr',
                            'Midnight'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'The Prophet (ﷺ) encouraged believers to take Suhoor because it contains?',
                        'options' => [
                            'Wealth',
                            'Blessings',
                            'Power',
                            'Reward only for scholars'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'The fast is broken at which prayer time?',
                        'options' => [
                            'Fajr',
                            'Dhuhr',
                            'Asr',
                            'Maghrib'
                        ],
                        'correct_index' => 3
                    ],
                    [
                        'question' => 'It is Sunnah to break the fast with?',
                        'options' => [
                            'Water only',
                            'Rice',
                            'Dates',
                            'Bread'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'A dua at the time of Iftar is?',
                        'options' => [
                            'Rejected',
                            'Accepted',
                            'Only accepted on Fridays',
                            'Only for scholars'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 2,
                'day_number' => 14,
                'title' => 'Fidyah and Kaffarah',
                'subtitle' => 'Compensation for Missed Fasts',
                'questions' => [
                    [
                        'question' => 'Fidyah is given when a person is unable to fast due to?',
                        'options' => [
                            'Laziness',
                            'Chronic illness or old age',
                            'Travel for vacation',
                            'Work schedule'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Kaffarah is required when someone?',
                        'options' => [
                            'Forgets and eats',
                            'Breaks the fast intentionally without valid reason',
                            'Sleeps during the day',
                            'Misses Suhoor'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'One form of Kaffarah for intentionally breaking a fast is?',
                        'options' => [
                            'Fasting 10 days',
                            'Fasting 30 days',
                            'Fasting 60 consecutive days',
                            'Giving voluntary charity only'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Fidyah is typically fulfilled by?',
                        'options' => [
                            'Praying extra rak’ahs',
                            'Feeding a poor person for each missed fast',
                            'Reciting Quran only',
                            'Donating clothes'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'If a sick person recovers after Ramadan, they must?',
                        'options' => [
                            'Pay fidyah only',
                            'Fast the missed days later',
                            'Do nothing',
                            'Repeat the entire Ramadan'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 3,
                'day_number' => 15,
                'title' => 'The Five Pillars of Islam',
                'subtitle' => 'Foundations of Islam',
                'questions' => [
                    [
                        'question' => 'How many pillars of Islam are there?',
                        'options' => [
                            '3',
                            '4',
                            '5',
                            '6'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Which of the following is the first pillar of Islam?',
                        'options' => [
                            'Salah (Prayer)',
                            'Zakat (Charity)',
                            'Shahadah (Testimony of Faith)',
                            'Hajj (Pilgrimage)'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Which pillar of Islam is performed five times daily?',
                        'options' => [
                            'Zakat',
                            'Salah',
                            'Sawm',
                            'Hajj'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Hajj is obligatory for Muslims who are?',
                        'options' => [
                            'Elderly only',
                            'Financially and physically able',
                            'Students only',
                            'Scholars only'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Fasting during Ramadan is known as?',
                        'options' => [
                            'Salah',
                            'Zakat',
                            'Sawm',
                            'Shahadah'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],
            [
                'week_id' => 3,
                'day_number' => 16,
                'title' => 'Belief in Angels',
                'subtitle' => 'Faith in the Unseen',
                'questions' => [
                    [
                        'question' => 'Belief in angels is part of which foundation of Islam?',
                        'options' => [
                            'Five Pillars of Islam',
                            'Six Articles of Faith',
                            'Four Schools of Thought',
                            'Five Daily Prayers'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Which angel delivered revelation to the Prophet (ﷺ)?',
                        'options' => [
                            'Mikail (AS)',
                            'Israfil (AS)',
                            'Jibreel (AS)',
                            'Malik (AS)'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Which angel is responsible for blowing the trumpet on the Day of Judgment?',
                        'options' => [
                            'Jibreel (AS)',
                            'Israfil (AS)',
                            'Mikail (AS)',
                            'Ridwan (AS)'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'The angels who record human deeds are known as?',
                        'options' => [
                            'Munkar and Nakir',
                            'Kiraman Katibeen',
                            'Mikail and Israfil',
                            'Ridwan and Malik'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Angels are created from?',
                        'options' => [
                            'Clay',
                            'Fire',
                            'Light',
                            'Water'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],
            [
                'week_id' => 3,
                'day_number' => 17,
                'title' => 'Prophets in Islam',
                'subtitle' => 'Messengers of Allah',
                'questions' => [
                    [
                        'question' => 'How many prophets are mentioned by name in the Qur’an?',
                        'options' => [
                            '10',
                            '15',
                            '25',
                            '40'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Who is considered the final Prophet in Islam?',
                        'options' => [
                            'Prophet Isa (AS)',
                            'Prophet Musa (AS)',
                            'Prophet Muhammad (ﷺ)',
                            'Prophet Ibrahim (AS)'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Which prophet is known as “Khalilullah” (Friend of Allah)?',
                        'options' => [
                            'Prophet Nuh (AS)',
                            'Prophet Ibrahim (AS)',
                            'Prophet Yusuf (AS)',
                            'Prophet Dawud (AS)'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Which prophet received the Tawrah (Torah)?',
                        'options' => [
                            'Prophet Isa (AS)',
                            'Prophet Musa (AS)',
                            'Prophet Dawud (AS)',
                            'Prophet Sulaiman (AS)'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Which prophet was swallowed by a large fish?',
                        'options' => [
                            'Prophet Yunus (AS)',
                            'Prophet Zakariya (AS)',
                            'Prophet Ayyub (AS)',
                            'Prophet Hud (AS)'
                        ],
                        'correct_index' => 0
                    ],
                ]
            ],
            [
                'week_id' => 3,
                'day_number' => 18,
                'title' => 'Revealed Books of Allah',
                'subtitle' => 'Divine Scriptures Overview',
                'questions' => [
                    [
                        'question' => 'How many revealed books are mentioned in Islam?',
                        'options' => [
                            '2',
                            '3',
                            '4',
                            '5'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The Tawrah (Torah) was revealed to which prophet?',
                        'options' => [
                            'Prophet Isa (AS)',
                            'Prophet Musa (AS)',
                            'Prophet Dawud (AS)',
                            'Prophet Ibrahim (AS)'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'The Zabur (Psalms) was revealed to?',
                        'options' => [
                            'Prophet Musa (AS)',
                            'Prophet Isa (AS)',
                            'Prophet Dawud (AS)',
                            'Prophet Yusuf (AS)'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The Injil (Gospel) was revealed to?',
                        'options' => [
                            'Prophet Isa (AS)',
                            'Prophet Musa (AS)',
                            'Prophet Nuh (AS)',
                            'Prophet Hud (AS)'
                        ],
                        'correct_index' => 0
                    ],
                    [
                        'question' => 'The final revealed book sent to humanity is?',
                        'options' => [
                            'Tawrah',
                            'Zabur',
                            'Injil',
                            'Qur’an'
                        ],
                        'correct_index' => 3
                    ],
                ]
            ],
            [
                'week_id' => 3,
                'day_number' => 19,
                'title' => 'Names and Attributes of Allah',
                'subtitle' => 'Asma ul Husna',
                'questions' => [
                    [
                        'question' => 'How many beautiful names (Asma ul Husna) are commonly known?',
                        'options' => [
                            '50',
                            '75',
                            '99',
                            '114'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Ar-Rahman means?',
                        'options' => [
                            'The Most Just',
                            'The Most Merciful',
                            'The All-Knowing',
                            'The Creator'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Al-Ghaffar refers to Allah as?',
                        'options' => [
                            'The Provider',
                            'The King',
                            'The Forgiving',
                            'The Strong'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Al-Alim means?',
                        'options' => [
                            'The All-Knowing',
                            'The Patient',
                            'The Generous',
                            'The Guide'
                        ],
                        'correct_index' => 0
                    ],
                    [
                        'question' => 'Al-Malik means?',
                        'options' => [
                            'The Sustainer',
                            'The King',
                            'The Protector',
                            'The Forgiver'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 3,
                'day_number' => 20,
                'title' => 'Sunnah Practices',
                'subtitle' => 'Prophetic Traditions',
                'questions' => [
                    [
                        'question' => 'Using the miswak is considered?',
                        'options' => [
                            'Obligatory',
                            'Sunnah',
                            'Forbidden',
                            'Makruh'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Which voluntary fast was regularly observed by the Prophet (ﷺ)?',
                        'options' => [
                            'Every Friday',
                            'Mondays and Thursdays',
                            'Only in Ramadan',
                            'First day of each month'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Praying Tahajjud is performed at what time?',
                        'options' => [
                            'After Fajr',
                            'After Dhuhr',
                            'After Isha before sleeping only',
                            'Late night before Fajr'
                        ],
                        'correct_index' => 3
                    ],
                    [
                        'question' => 'Greeting others with Salam is?',
                        'options' => [
                            'Optional cultural act',
                            'Strongly recommended Sunnah',
                            'Forbidden',
                            'Only for scholars'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Smiling at your brother is considered?',
                        'options' => [
                            'Charity',
                            'Weakness',
                            'Unnecessary',
                            'Makruh'
                        ],
                        'correct_index' => 0
                    ],
                ]
            ],
            [
                'week_id' => 3,
                'day_number' => 21,
                'title' => 'Good Character in Islam',
                'subtitle' => 'Islamic Moral Excellence',
                'questions' => [
                    [
                        'question' => 'The Prophet (ﷺ) said he was sent to perfect what?',
                        'options' => [
                            'Trade',
                            'Leadership',
                            'Good character',
                            'Warfare'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Which quality is highly emphasized in Islam?',
                        'options' => [
                            'Arrogance',
                            'Honesty',
                            'Pride',
                            'Anger'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Patience in Arabic is called?',
                        'options' => [
                            'Shukr',
                            'Sabr',
                            'Tawbah',
                            'Ikhlas'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Forgiving others is considered?',
                        'options' => [
                            'Weakness',
                            'Optional',
                            'A noble act rewarded by Allah',
                            'Only for leaders'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Backbiting (Gheebah) is?',
                        'options' => [
                            'Allowed in Ramadan',
                            'A major sin',
                            'Encouraged for justice',
                            'A minor mistake'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 4,
                'day_number' => 22,
                'title' => 'Repentance (Tawbah)',
                'subtitle' => 'Returning to Allah',
                'questions' => [
                    [
                        'question' => 'The Arabic word for repentance is?',
                        'options' => [
                            'Sabr',
                            'Shukr',
                            'Tawbah',
                            'Ikhlas'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Allah loves those who constantly?',
                        'options' => [
                            'Compete in wealth',
                            'Repent and purify themselves',
                            'Travel frequently',
                            'Argue for truth'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'A sincere Tawbah requires how many main conditions?',
                        'options' => [
                            '1',
                            '2',
                            '3',
                            '5'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'One condition of true repentance is?',
                        'options' => [
                            'Public announcement',
                            'Immediate regret',
                            'Charity only',
                            'Repeating the sin'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Allah’s mercy is described as?',
                        'options' => [
                            'Limited',
                            'Only for scholars',
                            'Greater than His wrath',
                            'Rare'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],
            [
                'week_id' => 4,
                'day_number' => 23,
                'title' => 'Duas in Ramadan',
                'subtitle' => 'Supplications in Ramadan',
                'questions' => [
                    [
                        'question' => 'The dua at the time of breaking fast begins with?',
                        'options' => [
                            'Allahumma barik lana',
                            'Bismillah ir-Rahman ir-Rahim',
                            'Allahumma inni laka sumtu',
                            'Rabbana atina fid-dunya'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Which dua was taught for Laylatul Qadr?',
                        'options' => [
                            'Rabbighfir li waliwalidayya',
                            'Allahumma innaka afuwwun tuhibbul afwa fa’fu anni',
                            'SubhanAllahi wa bihamdihi',
                            'Astaghfirullah wa atubu ilayh'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'The time of Iftar is considered a moment when duas are?',
                        'options' => [
                            'Rejected',
                            'Accepted',
                            'Delayed only',
                            'Only accepted on Fridays'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Dua is described as the?',
                        'options' => [
                            'Weapon of the believer',
                            'Optional act',
                            'Practice of scholars only',
                            'Last act of worship'
                        ],
                        'correct_index' => 0
                    ],
                    [
                        'question' => 'Raising hands while making dua is?',
                        'options' => [
                            'Forbidden',
                            'Sunnah',
                            'Obligatory',
                            'Disliked'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 4,
                'day_number' => 24,
                'title' => 'Sadaqah (Voluntary Charity)',
                'subtitle' => 'Voluntary Acts of Giving',
                'questions' => [
                    [
                        'question' => 'Sadaqah refers to?',
                        'options' => [
                            'Obligatory charity only',
                            'Voluntary charity',
                            'Tax payment',
                            'Business profit'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Giving charity secretly is considered?',
                        'options' => [
                            'Better in many cases',
                            'Invalid',
                            'Forbidden',
                            'Required publicly'
                        ],
                        'correct_index' => 0
                    ],
                    [
                        'question' => 'Charity given during Ramadan is?',
                        'options' => [
                            'Reduced in reward',
                            'Equal to other months',
                            'Multiplied in reward',
                            'Not accepted'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Even a smile is considered?',
                        'options' => [
                            'Nothing',
                            'Charity',
                            'Weakness',
                            'Makruh'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Sadaqah protects a believer from?',
                        'options' => [
                            'Wealth',
                            'Punishment and hardship',
                            'Prayer',
                            'Fasting'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 4,
                'day_number' => 25,
                'title' => 'Night Prayer (Tahajjud & Qiyam)',
                'subtitle' => 'Late Night Worship',
                'questions' => [
                    [
                        'question' => 'Tahajjud prayer is performed during which time?',
                        'options' => [
                            'After Fajr',
                            'After Dhuhr',
                            'After Isha immediately',
                            'Late night before Fajr'
                        ],
                        'correct_index' => 3
                    ],
                    [
                        'question' => 'Tahajjud is classified as?',
                        'options' => [
                            'Fard',
                            'Wajib',
                            'Sunnah',
                            'Makruh'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The last third of the night is known as a time when?',
                        'options' => [
                            'Duas are rejected',
                            'Allah descends in a manner befitting His majesty',
                            'Prayer is invalid',
                            'Angels stop recording deeds'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Qiyam-ul-Layl refers to?',
                        'options' => [
                            'Night prayer',
                            'Friday prayer',
                            'Funeral prayer',
                            'Travel prayer'
                        ],
                        'correct_index' => 0
                    ],
                    [
                        'question' => 'The Prophet (ﷺ) was consistent in praying Tahajjud?',
                        'options' => [
                            'Yes',
                            'No',
                            'Only in Ramadan',
                            'Only on Fridays'
                        ],
                        'correct_index' => 0
                    ],
                ]
            ],
            [
                'week_id' => 4,
                'day_number' => 26,
                'title' => 'Etiquettes of Fasting',
                'subtitle' => 'Fasting Manners Guide',
                'questions' => [
                    [
                        'question' => 'Fasting is not only abstaining from food but also from?',
                        'options' => [
                            'Sleep',
                            'Work',
                            'Bad speech and sinful behavior',
                            'Exercise'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Backbiting while fasting?',
                        'options' => [
                            'Is allowed',
                            'Reduces reward',
                            'Is recommended',
                            'Breaks the fast automatically'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'If someone insults you while fasting, you should say?',
                        'options' => [
                            'I am fasting',
                            'You are wrong',
                            'Stay silent only',
                            'Argue back'
                        ],
                        'correct_index' => 0
                    ],
                    [
                        'question' => 'Lying during fasting?',
                        'options' => [
                            'Is acceptable',
                            'Invalidates reward',
                            'Is rewarded',
                            'Has no effect'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Maintaining patience during fasting is?',
                        'options' => [
                            'Optional',
                            'Encouraged and rewarded',
                            'Only for scholars',
                            'Not necessary'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 4,
                'day_number' => 27,
                'title' => 'Signs of Laylatul Qadr',
                'subtitle' => 'Identifying the Blessed Night',
                'questions' => [
                    [
                        'question' => 'Laylatul Qadr is most likely to occur in which part of Ramadan?',
                        'options' => [
                            'First 10 days',
                            'Middle 10 days',
                            'Last 10 days',
                            'After Eid'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Laylatul Qadr is commonly sought on which nights?',
                        'options' => [
                            'Even nights',
                            'Odd nights',
                            'Only the 15th night',
                            'Only the 29th night'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'One sign of Laylatul Qadr is that the night is?',
                        'options' => [
                            'Stormy and loud',
                            'Extremely cold',
                            'Peaceful and calm',
                            'Very bright like day'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'The sunrise after Laylatul Qadr appears?',
                        'options' => [
                            'With strong rays',
                            'Without strong rays',
                            'Red in color',
                            'Completely dark'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Laylatul Qadr is better than?',
                        'options' => [
                            '100 days',
                            '1 year',
                            '1000 months',
                            '500 months'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],
            [
                'week_id' => 4,
                'day_number' => 28,
                'title' => 'Gratitude (Shukr)',
                'subtitle' => 'Living with Thankfulness',
                'questions' => [
                    [
                        'question' => 'The Arabic word for gratitude is?',
                        'options' => [
                            'Sabr',
                            'Shukr',
                            'Tawbah',
                            'Ikhlas'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Allah promises to increase blessings for those who?',
                        'options' => [
                            'Complain frequently',
                            'Are grateful',
                            'Travel often',
                            'Argue constantly'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Gratitude should be shown through?',
                        'options' => [
                            'Words only',
                            'Actions only',
                            'Heart, words, and actions',
                            'Silence only'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Being ungrateful for blessings is called?',
                        'options' => [
                            'Kufr al-Ni’mah',
                            'Sabr',
                            'Ihsan',
                            'Taqwa'
                        ],
                        'correct_index' => 0
                    ],
                    [
                        'question' => 'The Prophet (ﷺ) would pray extra at night to show?',
                        'options' => [
                            'Fear',
                            'Gratitude',
                            'Leadership',
                            'Strength'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 5,
                'day_number' => 29,
                'title' => 'Preparing for Eid',
                'subtitle' => 'Eid Preparation Essentials',
                'questions' => [
                    [
                        'question' => 'Before going to Eid prayer, it is Sunnah to perform?',
                        'options' => [
                            'Extra fasting',
                            'Ghusl (ritual bath)',
                            'Travel',
                            'Sleep'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'It is recommended to wear what on Eid?',
                        'options' => [
                            'Old clothes',
                            'Simple clothes only',
                            'Best available clothes',
                            'Work clothes'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Takbeer should be recited on the way to?',
                        'options' => [
                            'Work',
                            'Market',
                            'Eid prayer',
                            'Home'
                        ],
                        'correct_index' => 2
                    ],
                    [
                        'question' => 'Zakat al-Fitr must be given before?',
                        'options' => [
                            'Ramadan begins',
                            'Eid prayer',
                            'Maghrib',
                            'Tahajjud'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Visiting relatives on Eid is considered?',
                        'options' => [
                            'Discouraged',
                            'Sunnah and good practice',
                            'Obligatory only',
                            'Not allowed'
                        ],
                        'correct_index' => 1
                    ],
                ]
            ],
            [
                'week_id' => 5,
                'day_number' => 30,
                'title' => 'Ramadan Reflection and Consistency',
                'subtitle' => 'Post Ramadan Growth',
                'questions' => [
                    [
                        'question' => 'One major lesson of Ramadan is to develop?',
                        'options' => [
                            'Wealth',
                            'Self-discipline',
                            'Popularity',
                            'Authority'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Good deeds performed in Ramadan should?',
                        'options' => [
                            'Stop after Eid',
                            'Continue after Ramadan',
                            'Be shown publicly only',
                            'Be reduced gradually'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'The best deeds in Islam are those that are?',
                        'options' => [
                            'Large but rare',
                            'Consistent even if small',
                            'Done only in Ramadan',
                            'Done publicly'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'After Ramadan, Muslims should continue?',
                        'options' => [
                            'Neglecting prayer',
                            'Regular worship and good character',
                            'Only fasting on Eid',
                            'Avoiding charity'
                        ],
                        'correct_index' => 1
                    ],
                    [
                        'question' => 'Ramadan helps strengthen a believer’s?',
                        'options' => [
                            'Business skills',
                            'Physical strength only',
                            'Relationship with Allah',
                            'Political power'
                        ],
                        'correct_index' => 2
                    ],
                ]
            ],

        ];
    }
}
