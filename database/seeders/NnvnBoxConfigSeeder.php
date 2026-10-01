<?php

namespace Database\Seeders;

use App\MiniApps\NnvnApisGo\Models\BoxConfig;
use Illuminate\Database\Seeder;

class NnvnBoxConfigSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 16; $i++) {
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

        // Deactivate old boxes (16+) if they exist
        BoxConfig::where('box_index', '>=', 16)->update(['is_active' => false]);
    }
}
