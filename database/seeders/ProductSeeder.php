<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $products = [
            [
                'name' => 'Buket Bunga Wisuda Mini',
                'description' => 'Buket bunga segar ukuran mini dengan sentuhan pita hitam emas. Cocok untuk hadiah wisuda.',
                'price' => 185000,
                'category' => 'Fresh Flower',
                'category_id' => $categories['Fresh Flower']->id,
                'stock' => 20,
            ],
            [
                'name' => 'Buket Boneka Wisuda',
                'description' => 'Buket wisuda berisi boneka teddy bear dengan bunga mawar putih dan pita toga.',
                'price' => 250000,
                'category' => 'Buket Boneka',
                'category_id' => $categories['Buket Boneka']->id,
                'stock' => 12,
            ],
            [
                'name' => 'Buket Ulang Tahun Klasik',
                'description' => 'Buket ulang tahun dengan bunga matahari, mawar merah, dan baby breath. Sempurna untuk rayakan hari spesial.',
                'price' => 275000,
                'category' => 'Fresh Flower',
                'category_id' => $categories['Fresh Flower']->id,
                'stock' => 25,
            ],
            [
                'name' => 'Buket Ulang Tahun Premium',
                'description' => 'Buket ulang tahun mewah dengan 12 tangkai mawar merah, eucalyptus, dan wrapping kertas premium.',
                'price' => 450000,
                'category' => 'Hand Bouquet',
                'category_id' => $categories['Hand Bouquet']->id,
                'stock' => 10,
            ],
            [
                'name' => 'Buket Pernikahan Sweet Bridal',
                'description' => 'Buket pengantin dengan lili putih, mawar putih, dan baby breath. Elegan dan timeless.',
                'price' => 550000,
                'category' => 'Hand Bouquet',
                'category_id' => $categories['Hand Bouquet']->id,
                'stock' => 8,
            ],
            [
                'name' => 'Buket Anniversary Rose Gold',
                'description' => 'Buket anniversary eksklusif dengan mawar rose gold, eucalyptus silver, dan pita satin.',
                'price' => 375000,
                'category' => 'Hand Bouquet',
                'category_id' => $categories['Hand Bouquet']->id,
                'stock' => 15,
            ],
            [
                'name' => 'Money Bouquet Uang 50 Ribu',
                'description' => 'Buket uang berisi 10 lembar uang 50 Ribu yang ditata artistik dengan bunga sintetis.',
                'price' => 650000,
                'category' => 'Buket Uang',
                'category_id' => $categories['Buket Uang']->id,
                'stock' => 30,
            ],
            [
                'name' => 'Money Bouquet Uang 100 Ribu Mini',
                'description' => 'Buket uang mini berisi 5 lembar 100 Ribu, cocok untuk hadiah kondangan atau ulang tahun.',
                'price' => 700000,
                'category' => 'Buket Uang',
                'category_id' => $categories['Buket Uang']->id,
                'stock' => 20,
            ],
            [
                'name' => 'Buket Coklat Silverqueen',
                'description' => 'Buket coklat Silverqueen berbagai varian, dikemas menarik dengan bunga dan pita.',
                'price' => 150000,
                'category' => 'Buket Coklat',
                'category_id' => $categories['Buket Coklat']->id,
                'stock' => 40,
            ],
            [
                'name' => 'Buket Snack Campur',
                'description' => 'Buket berisi aneka snack favorit seperti Oreo, Chitato, Tango, dan coklat, dirangkai unik.',
                'price' => 200000,
                'category' => 'Buket Snack',
                'category_id' => $categories['Buket Snack']->id,
                'stock' => 35,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['name' => $data['name']],
                $data,
            );
        }
    }
}
