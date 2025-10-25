<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'barcode',
        'brand',
        'category',
        'packaging',
        'unit_size',
        'min_price',
        'max_price',
        'description',
        'photo_path',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'min_price' => 'float',
        'max_price' => 'float',
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];
}
