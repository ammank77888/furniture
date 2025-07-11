<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';
    protected $fillable = [
        'name', 'description', 'price', 'image', 'stock', 'image_path', 
        'category', 'tags', 'view_count', 'like_count'
    ];

    protected $casts = [
        'tags' => 'array',
        'view_count' => 'integer',
        'like_count' => 'integer'
    ];
} 