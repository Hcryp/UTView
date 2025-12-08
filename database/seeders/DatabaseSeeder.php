<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Hann',
            'username' => 'admin',
            'password' => bcrypt('admin')
        ]);
    }
}