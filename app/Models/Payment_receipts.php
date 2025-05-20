<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment_receipts extends Model
{
    protected $fillable = ['user_id', 'date', 'type', 'amount', 'notes'];

    protected $casts = [
        'date' => 'datetime',
        'type' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
