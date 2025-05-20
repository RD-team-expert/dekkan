<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class purchases extends Model
{
    protected $fillable = [
        'user_id', 'date', 'product_id', 'quantity',
        'purchase_price', 'selling_price'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(products::class);
    }
}
