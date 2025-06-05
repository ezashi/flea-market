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
            'postal_code' => '123-4567',
            'address' => '東京都千代田区',
            'building' => '千代田1-0',
        ]);

        $buyer = User::create([
            'name' => 'Test_buyer',
            'email' => 'buyer@example.com',
            'password' => Hash::make('12345678'),
            'postal_code' => '100-8111',
            'address' => '東京都千代田区',
            'building' => '千代田1-1',
        ]);
    }
}
