<?php

namespace Database\Seeders;

use App\Models\Node;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nodes = [
            // Main couple
            [
                'first_name' => 'ABU AYUB',
                'last_name' => null,
                'gender' => 'male',
                'dob' => '1922-06-17',
                'dod' => '2018-08-18',
            ],
            [
                'first_name' => 'AKHTARUNNESA',
                'last_name' => null,
                'gender' => 'female',
                'dob' => '1936-07-09',
                'dod' => null,
                'profile_photo' => null,
            ],
            
            // Children of ABU AYUB & AKHTARUNNESA
            [
                'first_name' => 'ABU SOHEL',
                'last_name' => null,
                'gender' => 'male',
                'dob' => '1959-02-17',
                'dod' => null,
            ],
            [
                'first_name' => 'ABU ESRAR',
                'last_name' => 'SHELLY',
                'gender' => 'male',
                'dob' => '1960-05-23',
                'dod' => null,
            ],
            [
                'first_name' => 'KAWSAR SABINA',
                'last_name' => null,
                'gender' => 'female',
                'dob' => '1961-08-28',
                'dod' => null,
            ],
            [
                'first_name' => 'ABU ASHRAF',
                'last_name' => 'SOHAN',
                'gender' => 'male',
                'dob' => '1963-11-28',
                'dod' => null,
            ],
            [
                'first_name' => 'SAYEEDA YASMIN',
                'last_name' => null,
                'gender' => 'female',
                'dob' => '1965-02-21',
                'dod' => '2016-07-22',
            ],
            [
                'first_name' => 'ABU SHAKIK',
                'last_name' => null,
                'gender' => 'male',
                'dob' => '1972-04-15',
                'dod' => null,
            ],
            [
                'first_name' => 'NAILA SHARMIN',
                'last_name' => null,
                'gender' => 'female',
                'dob' => '1974-08-21',
                'dod' => null,
            ],
            
            // Spouses
            [
                'first_name' => 'TASNEEM',
                'last_name' => 'RASHID',
                'gender' => 'female',
                'dob' => null,
                'dod' => null,
            ],
            [
                'first_name' => 'ATIAR',
                'last_name' => 'RAHMAN',
                'gender' => 'male',
                'dob' => null,
                'dod' => null,
            ],
            [
                'first_name' => 'LAILA FARZANA',
                'last_name' => 'Shipu',
                'gender' => 'female',
                'dob' => null,
                'dod' => null,
            ],
            [
                'first_name' => 'AL MASROOR',
                'last_name' => 'KHAN',
                'gender' => 'male',
                'dob' => null,
                'dod' => null,
            ],
            [
                'first_name' => 'FARZANA',
                'last_name' => 'KABIR',
                'gender' => 'female',
                'dob' => null,
                'dod' => null,
            ],
            [
                'first_name' => 'SYED HABIB',
                'last_name' => 'ALI',
                'gender' => 'male',
                'dob' => null,
                'dod' => null,
            ],
            
            // Grandchildren - ABU SOHEL line
            [
                'first_name' => 'ABU SOWAD',
                'last_name' => null,
                'gender' => 'male',
                'dob' => '1989-07-12',
                'dod' => null,
            ],
            
            // Grandchildren - ABU ESRAR line
            [
                'first_name' => 'FATEMA TUT TAHERA',
                'last_name' => 'JAMI',
                'gender' => 'female',
                'dob' => '1988-09-04',
                'dod' => null,
            ],
            [
                'first_name' => 'SAYED AL AMIN',
                'last_name' => 'ODRY',
                'gender' => 'male',
                'dob' => null,
                'dod' => null,
            ],
            [
                'first_name' => 'IMANEH IMTESAL',
                'last_name' => 'IMANI',
                'gender' => 'female',
                'dob' => '2022-01-23',
                'dod' => null,
            ],
            [
                'first_name' => 'IMARAH',
                'last_name' => 'ILMEEYAT',
                'gender' => 'female',
                'dob' => '2024-02-06',
                'dod' => null,
            ],
            [
                'first_name' => 'FATEMA TUT ZOHRA',
                'last_name' => 'NAINA',
                'gender' => 'female',
                'dob' => '1995-10-27',
                'dod' => null,
            ],
            [
                'first_name' => 'SHADMAN SHARIK',
                'last_name' => 'SHOUMIK',
                'gender' => 'male',
                'dob' => null,
                'dod' => null,
            ],
            [
                'first_name' => 'EVRAN AZAH',
                'last_name' => 'AHMED',
                'gender' => 'male',
                'dob' => '2024-04-02',
                'dod' => null,
            ],
            [
                'first_name' => 'FATEMA TAIABA',
                'last_name' => 'SANA',
                'gender' => 'female',
                'dob' => '2024-04-07',
                'dod' => null,
            ],
            
            // Grandchildren - KAWSAR SABINA line
            [
                'first_name' => 'IQBAL AZIZ MUTTAQI',
                'last_name' => 'ANIK',
                'gender' => 'male',
                'dob' => '1988-06-14',
                'dod' => null,
            ],
            [
                'first_name' => 'TASNIA',
                'last_name' => 'SHARIF',
                'gender' => 'female',
                'dob' => null,
                'dod' => null,
            ],
            [
                'first_name' => 'ILHAN JUNAINA',
                'last_name' => 'RAHMAN',
                'gender' => 'female',
                'dob' => '2020-07-12',
                'dod' => null,
            ],
            
            // Grandchildren - ABU ASHRAF line
            [
                'first_name' => 'ABU SAKIF',
                'last_name' => 'TAHMIN',
                'gender' => 'male',
                'dob' => '1996-03-12',
                'dod' => null,
            ],
            [
                'first_name' => 'KAZI',
                'last_name' => 'NAWMIN NAWMY',
                'gender' => 'female',
                'dob' => null,
                'dod' => null,
            ],
            [
                'first_name' => 'ABU AKIF',
                'last_name' => 'TAHSIN',
                'gender' => 'male',
                'dob' => '2002-02-06',
                'dod' => null,
            ],
            
            // Grandchildren - SAYEEDA YASMIN line
            [
                'first_name' => 'SHAAN MUBERA',
                'last_name' => 'KHAN MIM',
                'gender' => 'female',
                'dob' => '1988-12-27',
                'dod' => null,
            ],
            [
                'first_name' => 'MUHAMMAD MUNSWARIM',
                'last_name' => 'KHAN',
                'gender' => 'male',
                'dob' => '1999-02-10',
                'dod' => null,
            ],
            
            // Grandchildren - ABU SHAKIK line
            [
                'first_name' => 'TASFIA AFSARA',
                'last_name' => 'SAHIFA',
                'gender' => 'female',
                'dob' => '2003-12-06',
                'dod' => '2019-08-29',
            ],
            [
                'first_name' => 'TAHIYA AFSARA',
                'last_name' => 'FAIZAH',
                'gender' => 'female',
                'dob' => '2007-08-28',
                'dod' => null,
            ],
            
            // Grandchildren - NAILA SHARMIN line
            [
                'first_name' => 'SYED AHNAF',
                'last_name' => 'ALI HASIN',
                'gender' => 'male',
                'dob' => '1998-06-22',
                'dod' => null,
            ],
            [
                'first_name' => 'MEHER NIGAR AFRIN',
                'last_name' => 'MEHNAZ',
                'gender' => 'female',
                'dob' => '2025-09-19',
                'dod' => null,
            ],
            [
                'first_name' => 'SYEDA HUMAIRA',
                'last_name' => 'ADIBA',
                'gender' => 'female',
                'dob' => '2002-01-16',
                'dod' => null,
            ],
        ];

        foreach ($nodes as $node) {
            Node::create($node);
        }
        
        $this->command->info('Nodes seeded successfully!');
    }
}
