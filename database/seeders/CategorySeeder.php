<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryFieldOption;
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
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true,
                     'field_options' => [['name' => 'Small', 'price' => 0], ['name' => 'Medium', 'price' => 15000], ['name' => 'Large', 'price' => 30000]]],
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
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true,
                     'field_options' => [['name' => 'Small', 'price' => 0], ['name' => 'Medium', 'price' => 15000], ['name' => 'Large', 'price' => 30000]]],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Warna Bunga', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                    ['label' => 'Catatan Tambahan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Buket Boneka',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true,
                     'field_options' => [['name' => 'Small', 'price' => 0], ['name' => 'Medium', 'price' => 20000], ['name' => 'Large', 'price' => 35000]]],
                    ['label' => 'Jenis Isi', 'type' => 'select', 'options' => 'Boneka, Boneka+Bunga, Boneka+Uang, Boneka+Coklat', 'is_required' => true,
                     'field_options' => [['name' => 'Boneka', 'price' => 0], ['name' => 'Boneka+Bunga', 'price' => 25000], ['name' => 'Boneka+Uang', 'price' => 50000], ['name' => 'Boneka+Coklat', 'price' => 20000]]],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Buket Snack',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true,
                     'field_options' => [['name' => 'Small', 'price' => 0], ['name' => 'Medium', 'price' => 15000], ['name' => 'Large', 'price' => 25000]]],
                    ['label' => 'Jenis Snack', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Catatan Tambahan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Buket Coklat',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true,
                     'field_options' => [['name' => 'Small', 'price' => 0], ['name' => 'Medium', 'price' => 15000], ['name' => 'Large', 'price' => 25000]]],
                    ['label' => 'Jenis Coklat', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Catatan Tambahan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Hand Bouquet',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true,
                     'field_options' => [['name' => 'Small', 'price' => 0], ['name' => 'Medium', 'price' => 20000], ['name' => 'Large', 'price' => 40000]]],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Warna Bunga', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Thumbelina Bouquet',
                'fields' => [
                    ['label' => 'Ukuran', 'type' => 'select', 'options' => 'Small, Medium, Large', 'is_required' => true,
                     'field_options' => [['name' => 'Small', 'price' => 0], ['name' => 'Medium', 'price' => 10000], ['name' => 'Large', 'price' => 20000]]],
                    ['label' => 'Warna Bunga', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Kartu Ucapan', 'type' => 'text', 'options' => null, 'is_required' => false],
                ],
            ],
            [
                'name' => 'Buket Uang',
                'fields' => [
                    ['label' => 'Total Nominal', 'type' => 'select', 'options' => 'Rp100.000, Rp200.000, Rp300.000, Rp500.000, Rp1.000.000, Nominal Lain', 'is_required' => true,
                     'field_options' => [
                        ['name' => 'Rp100.000', 'price' => 0],
                        ['name' => 'Rp200.000', 'price' => 0],
                        ['name' => 'Rp300.000', 'price' => 0],
                        ['name' => 'Rp500.000', 'price' => 0],
                        ['name' => 'Rp1.000.000', 'price' => 0],
                        ['name' => 'Nominal Lain', 'price' => 0],
                     ]],
                    ['label' => 'Pecahan', 'type' => 'select', 'options' => 'Disesuaikan Florist, Rp100.000, Rp50.000, Rp20.000, Rp10.000, Rp5.000, Campuran', 'is_required' => true,
                     'field_options' => [
                        ['name' => 'Disesuaikan Florist', 'price' => 0],
                        ['name' => 'Rp100.000', 'price' => 0],
                        ['name' => 'Rp50.000', 'price' => 0],
                        ['name' => 'Rp20.000', 'price' => 0],
                        ['name' => 'Rp10.000', 'price' => 0],
                        ['name' => 'Rp5.000', 'price' => 0],
                        ['name' => 'Campuran', 'price' => 0],
                     ]],
                    ['label' => 'Warna Wrapping', 'type' => 'text', 'options' => null, 'is_required' => true],
                    ['label' => 'Dekorasi Tambahan', 'type' => 'checkbox', 'options' => 'Bunga, Coklat, Boneka', 'is_required' => false,
                     'field_options' => [['name' => 'Bunga', 'price' => 25000], ['name' => 'Coklat', 'price' => 20000], ['name' => 'Boneka', 'price' => 35000]]],
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
                $catField = $category->fields()->firstOrCreate(
                    ['label' => $field['label']],
                    ['type' => $field['type'], 'options' => $field['options'], 'is_required' => $field['is_required']],
                );

                if (!empty($field['field_options'])) {
                    foreach ($field['field_options'] as $opt) {
                        CategoryFieldOption::firstOrCreate(
                            ['category_field_id' => $catField->id, 'name' => $opt['name']],
                            ['price' => $opt['price']],
                        );
                    }
                }
            }
        }
    }
}
