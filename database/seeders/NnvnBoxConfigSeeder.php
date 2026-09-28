<?php

namespace Database\Seeders;

use App\MiniApps\NnvnApisGo\Models\BoxConfig;
use Illuminate\Database\Seeder;

class NnvnBoxConfigSeeder extends Seeder
{
    public function run(): void
    {
        $boxes = [];

        for ($i = 0; $i < 15; $i++) {
            $boxes[] = [
                'box_index' => $i,
                'url' => "https://example.com/box-{$i}",
                'label' => "Box {$i}",
                'is_active' => true,
            ];
        }

        foreach ($boxes as $box) {
            BoxConfig::firstOrCreate(
                ['box_index' => $box['box_index']],
                $box
            );
        }
    }
}
