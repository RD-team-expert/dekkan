<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $fillable = [
        'user_id', 'name', 'category', 'image_url',
        'quantity_alert', 'min_order', 'stock_quantity','barcode'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(purchases::class);
    }

    public function sales()
    {
        return $this->hasMany(sales::class);
    }

    public function latestPurchase()
    {
        return $this->hasOne(purchases::class, 'product_id')->latest('created_at')->select('product_id', 'selling_price');
    }

}
