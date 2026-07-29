<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fresh Flower',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Warna Bunga', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                    ['label' => 'Catatan Tambahan', 'type' => 'text', 'options' => null, 'is_required' => false],
                    ['label' => 'Upload Referensi', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Artificial Flower',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Warna Bunga', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                    ['label' => 'Catatan Tambahan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Buket Boneka',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true],
                    ['label' => 'Jenis Isi', 'type' => 'select', 'options' => 'Boneka, Boneka+Bunga, Boneka+Uang, Boneka+Coklat', 'is_required' => true],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Buket Snack',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true],
                    ['label' => 'Jenis Snack', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Catatan Tambahan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Buket Coklat',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true],
                    ['label' => 'Jenis Coklat', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Catatan Tambahan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Hand Bouquet',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Warna Bunga', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Thumbelina Bouquet',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true],
                    ['label' => 'Warna Bunga', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Buket Uang',
                'fields' => [
                    ['label' => 'Total Nominal', 'type' => 'select', 'options' => 'Rp100.000, Rp200.000, Rp300.000, Rp500.000, Rp1.000.000, Nominal Lain', 'is_required' => true],
                    ['label' => 'Pecahan', 'type' => 'select', 'options' => 'Disesuaikan Florist, Rp100.000, Rp50.000, Rp20.000, Rp10.000, Rp5.000, Campuran', 'is_required' => true],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Dekorasi Tambahan', 'type' => 'checkbox', 'options' => 'Bunga, Coklat, Boneka', 'is_required' => false],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                    ['label' => 'Catatan Tambahan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $category = Category::firstOrCreate(
                ['name' => $catData['name']],
                ['slug' => Str::slug($catData['name'])],
            );

            foreach ($catData['fields'] as $field) {
                $category->fields()->firstOrCreate(
                    ['label' => $field['label']],
                    $field,
                );
            }
        }
    }
}
