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
            'name' => 'Test_seller',
            'email' => 'seller@example.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        $buyer = User::create([
            'name' => 'Test_buyer',
            'email' => 'buyer@example.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
    }
}
