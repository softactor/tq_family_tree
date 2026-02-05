<?php

namespace Database\Seeders;

use App\Models\Node;
use App\Models\Relationship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RelationshipSeeder extends Seeder
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
        
        $relationships = [];
        
        // Main couple - ABU AYUB and AKHTARUNNESA (spouses)
        if (isset($nodeMap['ABU AYUB']) && isset($nodeMap['AKHTARUNNESA'])) {
            $relationships[] = [
                'node1_id' => $nodeMap['ABU AYUB'],
                'node2_id' => $nodeMap['AKHTARUNNESA'],
                'relationship_type' => 'spouse',
            ];
        }
        
        // Children of ABU AYUB & AKHTARUNNESA
        $children = [
            'ABU SOHEL',
            'ABU ESRAR SHELLY',
            'KAWSAR SABINA',
            'ABU ASHRAF SOHAN',
            'SAYEEDA YASMIN',
            'ABU SHAKIK',
            'NAILA SHARMIN',
        ];
        
        foreach ($children as $childName) {
            if (isset($nodeMap[$childName]) && isset($nodeMap['ABU AYUB']) && isset($nodeMap['AKHTARUNNESA'])) {
                // Child relationship with father
                $relationships[] = [
                    'node1_id' => $nodeMap['ABU AYUB'],
                    'node2_id' => $nodeMap[$childName],
                    'relationship_type' => 'parent',
                ];
                // Child relationship with mother
                $relationships[] = [
                    'node1_id' => $nodeMap['AKHTARUNNESA'],
                    'node2_id' => $nodeMap[$childName],
                    'relationship_type' => 'parent',
                ];
            }
        }
        
        // Spouse relationships
        $spousePairs = [
            ['ABU ESRAR SHELLY', 'TASNEEM RASHID'],
            ['KAWSAR SABINA', 'ATIAR RAHMAN'],
            ['ABU ASHRAF SOHAN', 'LAILA FARZANA Shipu'],
            ['SAYEEDA YASMIN', 'AL MASROOR KHAN'],
            ['ABU SHAKIK', 'FARZANA KABIR'],
            ['NAILA SHARMIN', 'SYED HABIB ALI'],
            ['FATEMA TUT TAHERA JAMI', 'SAYED AL AMIN ODRY'],
            ['IQBAL AZIZ MUTTAQI ANIK', 'TASNIA SHARIF'],
            ['ABU SAKIF TAHMIN', 'KAZI NAWMIN NAWMY'],
            ['FATEMA TUT ZOHRA NAINA', 'SHADMAN SHARIK SHOUMIK'],
            ['SYED AHNAF ALI HASIN', 'MEHER NIGAR AFRIN MEHNAZ'],
        ];
        
        foreach ($spousePairs as $pair) {
            if (isset($nodeMap[$pair[0]]) && isset($nodeMap[$pair[1]])) {
                $relationships[] = [
                    'node1_id' => $nodeMap[$pair[0]],
                    'node2_id' => $nodeMap[$pair[1]],
                    'relationship_type' => 'spouse',
                ];
            }
        }
        
        // Parent-child relationships for grandchildren
        $parentChildPairs = [
            // ABU SOHEL's children
            ['ABU SOHEL', 'ABU SOWAD'],
            
            // ABU ESRAR's children
            ['ABU ESRAR SHELLY', 'FATEMA TUT TAHERA JAMI'],
            ['ABU ESRAR SHELLY', 'FATEMA TUT ZOHRA NAINA'],
            
            // FATEMA TUT TAHERA's children
            ['FATEMA TUT TAHERA JAMI', 'IMANEH IMTESAL IMANI'],
            ['FATEMA TUT TAHERA JAMI', 'IMARAH ILMEEYAT'],
            
            // FATEMA TUT ZOHRA's children
            ['FATEMA TUT ZOHRA NAINA', 'EVRAN AZAH AHMED'],
            ['FATEMA TUT ZOHRA NAINA', 'FATEMA TAIABA SANA'],
            
            // KAWSAR SABINA's children
            ['KAWSAR SABINA', 'IQBAL AZIZ MUTTAQI ANIK'],
            
            // IQBAL AZIZ's children
            ['IQBAL AZIZ MUTTAQI ANIK', 'ILHAN JUNAINA RAHMAN'],
            
            // ABU ASHRAF's children
            ['ABU ASHRAF SOHAN', 'ABU SAKIF TAHMIN'],
            ['ABU ASHRAF SOHAN', 'ABU AKIF TAHSIN'],
            
            // SAYEEDA YASMIN's children
            ['SAYEEDA YASMIN', 'SHAAN MUBERA KHAN MIM'],
            ['SAYEEDA YASMIN', 'MUHAMMAD MUNSWARIM KHAN'],
            
            // ABU SHAKIK's children
            ['ABU SHAKIK', 'TASFIA AFSARA SAHIFA'],
            ['ABU SHAKIK', 'TAHIYA AFSARA FAIZAH'],
            
            // NAILA SHARMIN's children
            ['NAILA SHARMIN', 'SYED AHNAF ALI HASIN'],
            ['NAILA SHARMIN', 'SYEDA HUMAIRA ADIBA'],
        ];
        
        foreach ($parentChildPairs as $pair) {
            if (isset($nodeMap[$pair[0]]) && isset($nodeMap[$pair[1]])) {
                $relationships[] = [
                    'node1_id' => $nodeMap[$pair[0]],
                    'node2_id' => $nodeMap[$pair[1]],
                    'relationship_type' => 'parent',
                ];
            }
        }
        
        // Insert all relationships
        foreach ($relationships as $relationship) {
            Relationship::create($relationship);
        }
        
        $this->command->info('Relationships seeded successfully!');
    }
}
