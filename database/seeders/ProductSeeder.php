<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Buket Mawar Merah Romantis',
                'description' => 'Buket mawar merah segar dengan 12 tangkai, dibungkus kraft paper coklat. Cocok untuk Valentine atau anniversary.',
                'price' => 350000,
                'category' => 'Mawar',
                'stock' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Buket Matahari Ceria',
                'description' => 'Buket bunga matahari ceria dengan sentuhan baby breath. Penuh semangat dan kebahagiaan.',
                'price' => 275000,
                'category' => 'Matahari',
                'stock' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Buket Lili Putih Elegan',
                'description' => 'Buket bunga lili putih yang anggun. Sempurna untuk acara formal atau ungkapan simpati.',
                'price' => 400000,
                'category' => 'Lili',
                'stock' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Buket Mixed Pastel Lembut',
                'description' => 'Perpaduan bunga pastel lembut: rose, carnation, dan chrysanthemum dalam nuansa pink dan peach.',
                'price' => 325000,
                'category' => 'Mixed',
                'stock' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Buket Lavender Impian',
                'description' => 'Buket bunga lavender dengan eucalyptus. Aroma menenangkan dan tampilan yang memukau.',
                'price' => 290000,
                'category' => 'Lavender',
                'stock' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Buket Carnation Pink Sayang',
                'description' => 'Buket carnation pink untuk Ibu dan orang tersayang. Melambangkan kasih sayang yang tulus.',
                'price' => 250000,
                'category' => 'Carnation',
                'stock' => 18,
                'is_active' => true,
            ],
            [
                'name' => 'Buket Tulip Belanda',
                'description' => 'Buket bunga tulip impor dari Belanda. Eksklusif dan berkelas untuk momen spesial.',
                'price' => 550000,
                'category' => 'Tulip',
                'stock' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Buket Daisy Kuning Ceria',
                'description' => 'Buket bunga daisy kuning yang ceria dan cerah. Sempurna untuk ucapan selamat dan semangat.',
                'price' => 220000,
                'category' => 'Daisy',
                'stock' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Buket Peony Mewah',
                'description' => 'Buket bunga peony import yang mewah. Pilihan sempurna untuk wedding atau lamaran.',
                'price' => 675000,
                'category' => 'Peony',
                'stock' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Buket Anggrek Bulan',
                'description' => 'Buket anggrek bulan putih yang elegan. Simbol kemewahan dan keanggunan.',
                'price' => 450000,
                'category' => 'Anggrek',
                'stock' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
