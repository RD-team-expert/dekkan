<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class salesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'integer',
            'date_time' => 'date',
            'product_id' => 'integer',
            'quantity' => 'integer',
            'total_products' => 'integer',
        ];
    }
}
