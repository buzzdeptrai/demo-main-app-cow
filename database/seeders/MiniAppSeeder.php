<?php

namespace Database\Seeders;

use App\Models\MiniApp;
use App\Models\User;
use Illuminate\Database\Seeder;

class MiniAppSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@cowork.local')->first();

        if (!$admin) {
            return;
        }

        $apps = [
            [
                'name' => 'Task Manager',
                'slug' => 'task-manager',
                'description' => 'A simple task management mini app for team collaboration.',
                'status' => 'active',
                'version' => '1.0.0',
                'api_rate_limit' => 60,
                'creator_id' => $admin->id,
            ],
            [
                'name' => 'Chat Bot',
                'slug' => 'chat-bot',
                'description' => 'AI-powered chatbot for customer support.',
                'status' => 'active',
                'version' => '2.1.0',
                'api_rate_limit' => 120,
                'creator_id' => $admin->id,
            ],
            [
                'name' => 'File Share',
                'slug' => 'file-share',
                'description' => 'Secure file sharing application for internal teams.',
                'status' => 'maintenance',
                'version' => '0.9.0',
                'api_rate_limit' => 30,
                'creator_id' => $admin->id,
            ],
        ];

        foreach ($apps as $appData) {
            MiniApp::firstOrCreate(
                ['slug' => $appData['slug']],
                $appData
            );
        }
    }
}
