<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentReceiptRequest extends FormRequest
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
            'type' => 'in:payment,receipt',
            'amount' => 'numeric',
            'notes' => 'string',
        ];
    }
}
