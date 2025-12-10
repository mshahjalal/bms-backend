<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'numeric|min:0',
            'due_date' => 'date',
            'status' => 'in:unpaid,paid',
        ];
    }
}
