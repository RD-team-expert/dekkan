<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{

    protected $fillable = [
        'user_id', 'date_time', 'product_id', 'quantity', 'total_products', 'total_price'
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class); // Changed 'products' to 'Product' for consistency
    }
}
