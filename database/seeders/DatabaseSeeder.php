<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Mobile Legends: Bang Bang',
                'slug' => 'mobile-legends',
                'category' => 'games',
                'publisher' => 'Moonton',
                'target_label' => 'User ID',
                'has_server_id' => true,
                'items' => [
                    ['name' => '86 Diamonds', 'price' => 19000],
                    ['name' => '172 Diamonds', 'price' => 37000],
                    ['name' => '257 Diamonds', 'price' => 55000],
                    ['name' => '344 Diamonds', 'price' => 73000],
                ],
            ],
            [
                'name' => 'Free Fire',
                'slug' => 'free-fire',
                'category' => 'games',
                'publisher' => 'Garena',
                'target_label' => 'Player ID',
                'has_server_id' => false,
                'items' => [
                    ['name' => '70 Diamonds', 'price' => 10000],
                    ['name' => '140 Diamonds', 'price' => 20000],
                    ['name' => '355 Diamonds', 'price' => 50000],
                    ['name' => '720 Diamonds', 'price' => 100000],
                ],
            ],
            [
                'name' => 'VALORANT',
                'slug' => 'valorant',
                'category' => 'games',
                'publisher' => 'Riot Games',
                'target_label' => 'Riot ID',
                'has_server_id' => false,
                'items' => [
                    ['name' => '125 VP', 'price' => 15000],
                    ['name' => '420 VP', 'price' => 50000],
                    ['name' => '700 VP', 'price' => 80000],
                    ['name' => '1.375 VP', 'price' => 150000],
                ],
            ],
            [
                'name' => 'Genshin Impact',
                'slug' => 'genshin-impact',
                'category' => 'games',
                'publisher' => 'HoYoverse',
                'target_label' => 'UID',
                'has_server_id' => true,
                'items' => [
                    ['name' => '60 Genesis Crystals', 'price' => 16000],
                    ['name' => '300 + 30 Genesis Crystals', 'price' => 79000],
                    ['name' => '980 + 110 Genesis Crystals', 'price' => 239000],
                    ['name' => '1.980 + 260 Genesis Crystals', 'price' => 479000],
                ],
            ],
            [
                'name' => 'Pulsa Telkomsel',
                'slug' => 'pulsa-telkomsel',
                'category' => 'pulsa',
                'publisher' => 'Telkomsel',
                'target_label' => 'Nomor HP',
                'has_server_id' => false,
                'items' => [
                    ['name' => 'Pulsa 5.000', 'price' => 6500],
                    ['name' => 'Pulsa 10.000', 'price' => 11500],
                    ['name' => 'Pulsa 25.000', 'price' => 26500],
                    ['name' => 'Pulsa 50.000', 'price' => 51500],
                    ['name' => 'Pulsa 100.000', 'price' => 101000],
                ],
            ],
            [
                'name' => 'Pulsa Indosat',
                'slug' => 'pulsa-indosat',
                'category' => 'pulsa',
                'publisher' => 'Indosat',
                'target_label' => 'Nomor HP',
                'has_server_id' => false,
                'items' => [
                    ['name' => 'Pulsa 5.000', 'price' => 6500],
                    ['name' => 'Pulsa 10.000', 'price' => 11000],
                    ['name' => 'Pulsa 25.000', 'price' => 26000],
                    ['name' => 'Pulsa 50.000', 'price' => 51000],
                    ['name' => 'Pulsa 100.000', 'price' => 100500],
                ],
            ],
            [
                'name' => 'Saldo DANA',
                'slug' => 'dana',
                'category' => 'ewallet',
                'publisher' => 'DANA',
                'target_label' => 'Nomor HP',
                'has_server_id' => false,
                'items' => [
                    ['name' => 'Saldo 10.000', 'price' => 11500],
                    ['name' => 'Saldo 20.000', 'price' => 21500],
                    ['name' => 'Saldo 50.000', 'price' => 51500],
                    ['name' => 'Saldo 100.000', 'price' => 101000],
                ],
            ],
            [
                'name' => 'Saldo GoPay',
                'slug' => 'gopay',
                'category' => 'ewallet',
                'publisher' => 'GoPay',
                'target_label' => 'Nomor HP',
                'has_server_id' => false,
                'items' => [
                    ['name' => 'Saldo 10.000', 'price' => 11500],
                    ['name' => 'Saldo 20.000', 'price' => 21500],
                    ['name' => 'Saldo 50.000', 'price' => 51500],
                    ['name' => 'Saldo 100.000', 'price' => 101000],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $items = $productData['items'];
            unset($productData['items']);

            $product = Product::query()->updateOrCreate(
                ['slug' => $productData['slug']],
                $productData + ['image_url' => null, 'is_active' => true],
            );

            foreach ($items as $item) {
                $product->items()->updateOrCreate(
                    ['name' => $item['name']],
                    $item + ['is_active' => true],
                );
            }
        }
    }
}
