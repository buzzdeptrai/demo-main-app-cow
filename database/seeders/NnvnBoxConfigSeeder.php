<?php

namespace Database\Seeders;

use App\MiniApps\NnvnApisGo\Models\BoxClick;
use App\MiniApps\NnvnApisGo\Models\BoxConfig;
use Illuminate\Database\Seeder;

class NnvnBoxConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Clear old box click data
        BoxClick::query()->delete();
        $links = [
            ['url' => 'https://pro.novonordisk.vn', 'label' => 'Novo Nordisk Pro'],
            ['url' => 'https://giamcansongkhoe.vn', 'label' => 'Giảm Cân Sống Khỏe'],
            ['url' => 'https://dieutrigiamcan.vn', 'label' => 'Điều Trị Giảm Cân'],
            ['url' => 'https://www.facebook.com/p/Novo-Nordisk-Vietnam-100063775214961/', 'label' => 'Facebook Novo Nordisk VN'],
            ['url' => 'https://zalo.me/s/81236361287844104/', 'label' => 'Zalo Novo Nordisk VN'],
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
