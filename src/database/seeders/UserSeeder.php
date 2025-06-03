<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seller = User::create([
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => 12345678,
            'email_verified_at' => now(),
        ]);
    }
}
