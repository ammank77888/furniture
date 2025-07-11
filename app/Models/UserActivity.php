<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class UserActivity extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'user_activities';
    
    protected $fillable = [
        'user_id',
        'product_id',
        'activity_type', // 'view', 'like', 'cart', 'purchase'
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
} 