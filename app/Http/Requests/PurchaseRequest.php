<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'integer',
            'date' => 'date',
            'product_id' => 'integer',
            'quantity' => 'integer',
            'purchase_price' => 'numeric',
            'selling_price' => 'numeric',
        ];
    }
}
