<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'barcode' => 'nullable|string|max:255|unique:products,barcode,' . $this->product?->id, // Allow unique barcodes
            'user_id' => 'integer',
            'name' => 'string|max:255',
            'image_url' => 'string|max:255',
            'quantity_alert' => 'integer',
            'min_order' => 'integer',
            'stock_quantity' => 'integer',
        ];
    }
}
