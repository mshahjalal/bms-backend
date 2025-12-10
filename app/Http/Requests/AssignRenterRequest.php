<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignRenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flat_id' => 'required|uuid|exists:flats,id',
            'renter_id' => 'required|uuid|exists:users,id',
        ];
    }
}
