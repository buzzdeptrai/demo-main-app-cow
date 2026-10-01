<?php

namespace Database\Seeders;

use App\MiniApps\NnvnApisGo\Models\BoxConfig;
use Illuminate\Database\Seeder;

class NnvnBoxConfigSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $boxIndex = $i;
            $boxNumber = $i + 1;

            BoxConfig::updateOrCreate(
                ['box_index' => $boxIndex],
                [
                    'url' => "https://www.novonordisk.vn/?box={$boxNumber}",
                    'label' => "Box {$boxNumber}",
                    'is_active' => true,
                ]
            );
        }

        // Deactivate old boxes (10-14) if they exist
        BoxConfig::where('box_index', '>=', 10)->update(['is_active' => false]);
    }
}
