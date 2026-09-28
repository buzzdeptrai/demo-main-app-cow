<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientAccountSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'client@nnvn.com'],
            [
                'name' => 'NNVN Client',
                'password' => Hash::make('Client@nnvn2026'),
                'status' => 'active',
            ]
        );

        if (!$user->hasRole('client')) {
            $user->assignRole('client');
        }
    }
}
