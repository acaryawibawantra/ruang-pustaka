<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id',
    'title',
    'author',
    'publisher',
    'publication_year',
    'isbn',
    'description',
    'cover_image',
    'total_stock',
    'available_stock',
    'is_active',
])]
class Book extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'total_stock' => 'integer',
            'available_stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
