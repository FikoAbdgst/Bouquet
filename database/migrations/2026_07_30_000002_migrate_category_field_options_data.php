<?php

use App\Models\CategoryFieldOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('category_fields')->orderBy('id')->each(function ($field) {
            if (!empty($field->options)) {
                $names = array_map('trim', explode(',', $field->options));
                foreach ($names as $name) {
                    if ($name !== '') {
                        CategoryFieldOption::create([
                            'category_field_id' => $field->id,
                            'name' => $name,
                            'price' => 0,
                        ]);
                    }
                }
            }
        });
    }

    public function down(): void
    {
        DB::table('category_field_options')->truncate();
    }
};
