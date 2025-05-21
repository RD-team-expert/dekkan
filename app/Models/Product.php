<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
        return $this->hasMany(purchase::class);
    }

    public function sales()
    {
        return $this->hasMany(sale::class);
    }

    public function latestPurchase()
    {
        return $this->hasOne(purchase::class, 'product_id')->latest('created_at')->select('product_id', 'selling_price');
    }

}
