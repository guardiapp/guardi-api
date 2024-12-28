<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBuildingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'residence_id' => ['required', 'exists:residences,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'floors_number' => ['nullable', 'integer'],
            'apartments_per_floor' => ['nullable', 'integer'],
            'information' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean']
        ];
    }
}
