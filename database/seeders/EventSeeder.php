<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Node;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all nodes with their names for easy reference
        $nodes = Node::all();
        $nodeMap = [];
        
        foreach ($nodes as $node) {
            $key = trim($node->first_name . ' ' . $node->last_name);
            $nodeMap[$key] = $node->id;
        }
        
        $events = [];
        
        // Birth events
        $birthEvents = [
            ['ABU AYUB', '1922-06-17', 'Birth'],
            ['AKHTARUNNESA', '1936-07-09', 'Birth'],
            ['ABU SOHEL', '1959-02-17', 'Birth'],
            ['ABU ESRAR SHELLY', '1960-05-23', 'Birth'],
            ['KAWSAR SABINA', '1961-08-28', 'Birth'],
            ['ABU ASHRAF SOHAN', '1963-11-28', 'Birth'],
            ['SAYEEDA YASMIN', '1965-02-21', 'Birth'],
            ['ABU SHAKIK', '1972-04-15', 'Birth'],
            ['NAILA SHARMIN', '1974-08-21', 'Birth'],
            ['ABU SOWAD', '1989-07-12', 'Birth'],
            ['FATEMA TUT TAHERA JAMI', '1988-09-04', 'Birth'],
            ['IMANEH IMTESAL IMANI', '2022-01-23', 'Birth'],
            ['IMARAH ILMEEYAT', '2024-02-06', 'Birth'],
            ['FATEMA TUT ZOHRA NAINA', '1995-10-27', 'Birth'],
            ['EVRAN AZAH AHMED', '2024-04-02', 'Birth'],
            ['FATEMA TAIABA SANA', '2024-04-07', 'Birth'],
            ['IQBAL AZIZ MUTTAQI ANIK', '1988-06-14', 'Birth'],
            ['ILHAN JUNAINA RAHMAN', '2020-07-12', 'Birth'],
            ['ABU SAKIF TAHMIN', '1996-03-12', 'Birth'],
            ['ABU AKIF TAHSIN', '2002-02-06', 'Birth'],
            ['SHAAN MUBERA KHAN MIM', '1988-12-27', 'Birth'],
            ['MUHAMMAD MUNSWARIM KHAN', '1999-02-10', 'Birth'],
            ['TASFIA AFSARA SAHIFA', '2003-12-06', 'Birth'],
            ['TAHIYA AFSARA FAIZAH', '2007-08-28', 'Birth'],
            ['SYED AHNAF ALI HASIN', '1998-06-22', 'Birth'],
            ['MEHER NIGAR AFRIN MEHNAZ', '2025-09-19', 'Birth'],
            ['SYEDA HUMAIRA ADIBA', '2002-01-16', 'Birth'],
        ];
        
        foreach ($birthEvents as $birthEvent) {
            if (isset($nodeMap[$birthEvent[0]])) {
                $events[] = [
                    'node_id' => $nodeMap[$birthEvent[0]],
                    'event_name' => 'Birth',
                    'event_date' => $birthEvent[1],
                    'description' => $birthEvent[2] . ' of ' . $birthEvent[0],
                ];
            }
        }
        
        // Marriage events
        $marriageEvents = [
            ['ABU AYUB', 'AKHTARUNNESA', '1958-01-09', 'Marriage of ABU AYUB and AKHTARUNNESA'],
            ['ABU ESRAR SHELLY', 'TASNEEM RASHID', '1986-12-18', 'Marriage of ABU ESRAR (SHELLY) and TASNEEM RASHID'],
            ['FATEMA TUT TAHERA JAMI', 'SAYED AL AMIN ODRY', '2015-11-19', 'Marriage of FATEMA TUT TAHERA (JAMI) and SAYED AL AMIN ODRY'],
            ['FATEMA TUT ZOHRA NAINA', 'SHADMAN SHARIK SHOUMIK', '2020-12-25', 'Marriage of FATEMA TUT ZOHRA (NAINA) and SHADMAN SHARIK SHOUMIK'],
            ['KAWSAR SABINA', 'ATIAR RAHMAN', '1986-01-17', 'Marriage of KAWSAR SABINA and ATIAR RAHMAN'],
            ['IQBAL AZIZ MUTTAQI ANIK', 'TASNIA SHARIF', '2018-05-04', 'Marriage of IQBAL AZIZ MUTTAQI (ANIK) and TASNIA SHARIF'],
            ['ABU ASHRAF SOHAN', 'LAILA FARZANA Shipu', '1991-09-05', 'Marriage of ABU ASHRAF (SOHAN) and LAILA FARZANA (Shipu)'],
            ['ABU SAKIF TAHMIN', 'KAZI NAWMIN NAWMY', '2026-01-24', 'Marriage of ABU SAKIF (TAHMIN) and KAZI NAWMIN (NAWMY)'],
            ['SAYEEDA YASMIN', 'AL MASROOR KHAN', '1987-04-15', 'Marriage of SAYEEDA YASMIN and AL MASROOR KHAN'],
            ['ABU SHAKIK', 'FARZANA KABIR', '2002-08-16', 'Marriage of ABU SHAKIK and FARZANA KABIR'],
            ['NAILA SHARMIN', 'SYED HABIB ALI', '1995-09-07', 'Marriage of NAILA SHARMIN and SYED HABIB ALI'],
            ['SYED AHNAF ALI HASIN', 'MEHER NIGAR AFRIN MEHNAZ', '2025-01-01', 'Marriage of SYED AHNAF ALI (HASIN) and MEHER NIGAR AFRIN MEHNAZ'],
        ];
        
        foreach ($marriageEvents as $marriageEvent) {
            if (isset($nodeMap[$marriageEvent[0]])) {
                $events[] = [
                    'node_id' => $nodeMap[$marriageEvent[0]],
                    'event_name' => 'Marriage',
                    'event_date' => $marriageEvent[2],
                    'description' => $marriageEvent[3],
                ];
            }
            if (isset($nodeMap[$marriageEvent[1]])) {
                $events[] = [
                    'node_id' => $nodeMap[$marriageEvent[1]],
                    'event_name' => 'Marriage',
                    'event_date' => $marriageEvent[2],
                    'description' => $marriageEvent[3],
                ];
            }
        }
        
        // Death events
        $deathEvents = [
            ['ABU AYUB', '2018-08-18', 'Death of ABU AYUB'],
            ['SAYEEDA YASMIN', '2016-07-22', 'Death of SAYEEDA YASMIN'],
            ['TASFIA AFSARA SAHIFA', '2019-08-29', 'Death of TASFIA AFSARA (SAHIFA)'],
        ];
        
        foreach ($deathEvents as $deathEvent) {
            if (isset($nodeMap[$deathEvent[0]])) {
                $events[] = [
                    'node_id' => $nodeMap[$deathEvent[0]],
                    'event_name' => 'Death',
                    'event_date' => $deathEvent[1],
                    'description' => $deathEvent[2],
                ];
            }
        }
        
        // Insert all events
        foreach ($events as $event) {
            Event::create($event);
        }
        
        $this->command->info('Events seeded successfully!');
    }
}
