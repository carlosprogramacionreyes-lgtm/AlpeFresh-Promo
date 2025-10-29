<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
protected $fillable = [
    'name',
    'slug',
'sku',
    'category',
    'presentation',
    'description',
    'is_active',
];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function mainImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->orderBy('position');
    }
}
