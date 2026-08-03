<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryFieldOption extends Model
{
    protected $fillable = [
        'category_field_id',
        'name',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
        ];
    }

    public function categoryField(): BelongsTo
    {
        return $this->belongsTo(CategoryField::class);
    }
}
