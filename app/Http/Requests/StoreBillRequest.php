<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flat_id' => 'required|uuid',
            'category_id' => 'required|uuid',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'in:unpaid,paid',
        ];
    }
}
