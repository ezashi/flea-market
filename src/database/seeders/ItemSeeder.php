<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seller = User::create([
            'name' => 'Test Seller',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $categories = [
            'ファッション',
            '家電',
            'インテリア',
            'レディース',
            'メンズ',
            'コスメ',
            '本',
            'ゲーム',
            'スポーツ',
            'キッチン',
            'ハンドメイド',
            'アクセサリー',
            'おもちゃ',
            'ベビー・キッズ',
        ];
        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }

        $conditions = [
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態が悪い',
        ];
        foreach ($conditions as $conditionName) {
            Condition::create(['name' => $conditionName]);
        }

        $items = [
            [
                'name' => '腕時計',
                'brand' => 'COACHTECH',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => '良好',
                'image' => 'storage/images/items/watch.jpg',
                'categories' => [1], // ファッション
            ],
            [
                'name' => 'HDD',
                'brand' => 'COACHTECH',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => '目立った傷や汚れなし',
                'image' => 'storage/images/items/hdd.jpg',
                'categories' => [2], // 家電
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => 'COACHTECH',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition' => 'やや傷や汚れあり',
                'image' => 'storage/images/items/onions.jpg',
                'categories' => [10], // キッチン
            ],
            [
                'name' => '革靴',
                'brand' => 'COACHTECH',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'condition' => '状態が悪い',
                'image' => 'storage/images/items/shoes.jpg',
                'categories' => [1], // ファッション
            ],
            [
                'name' => 'ノートPC',
                'brand' => 'COACHTECH',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'condition' => '良好',
                'image' => 'storage/images/items/laptop.jpg',
                'categories' => [2], // 家電
            ],
            [
                'name' => 'マイク',
                'brand' => 'COACHTECH',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'condition' => '目立った傷や汚れなし',
                'image' => 'storage/images/items/mic.jpg',
                'categories' => [2], // 家電
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => 'COACHTECH',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
                'image' => 'storage/images/items/bag.jpg',
                'categories' => [1], // ファッション
            ],
            [
                'name' => 'タンブラー',
                'brand' => 'COACHTECH',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'condition' => '状態が悪い',
                'image' => 'storage/images/items/tumbler.jpg',
                'categories' => [10], // キッチン
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => 'COACHTECH',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'condition' => '良好',
                'image' => 'storage/images/items/coffee_mill.jpg',
                'categories' => [10], // キッチン
            ],
            [
                'name' => 'メイクセット',
                'brand' => 'COACHTECH',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'condition' => '目立った傷や汚れなし',
                'image' => 'storage/images/items/makeup_set.jpg',
                'categories' => [6], // コスメ
            ],
        ];

        foreach ($items as $itemData) {
            $categoryIds = $itemData['categories'];
            unset($itemData['categories']);

            $itemData['seller_id'] = $seller->id;
            $item = Item::create($itemData);

            $item->categories()->attach($categoryIds);
        }
    }

    private function copyImages()
    {
        $images = [
            'watch.jpg',
            'hdd.jpg',
            'onions.jpg',
            'shoes.jpg',
            'laptop.jpg',
            'mic.jpg',
            'bag.jpg',
            'tumbler.jpg',
            'coffee_mill.jpg',
            'makeup_set.jpg',
        ];
    }
}
