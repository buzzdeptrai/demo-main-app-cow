<?php

namespace Database\Seeders;

use App\MiniApps\NnvnApisGo\Models\BoxConfig;
use Illuminate\Database\Seeder;

class NnvnBoxConfigSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            ['url' => 'https://www.novonordisk.vn/', 'label' => 'Novo Nordisk VN'],
            ['url' => 'https://www.novonordisk.com/about/the-novo-way.html', 'label' => 'The Novo Way'],
            ['url' => 'https://www.novonordisk.com/science-and-technology/ozempic.html', 'label' => 'Ozempic'],
            ['url' => 'https://www.novonordisk.com/science-and-technology/wegovy.html', 'label' => 'Wegovy'],
            ['url' => 'https://www.novonordisk.com/science-and-technology/ryzodeg.html', 'label' => 'Ryzodeg'],
        ];

        // Deactivate all old boxes
        BoxConfig::query()->update(['is_active' => false]);

        foreach ($links as $i => $link) {
            BoxConfig::updateOrCreate(
                ['box_index' => $i],
                [
                    'url' => $link['url'],
                    'label' => $link['label'],
                    'is_active' => true,
                ]
            );
        }
    }
}
