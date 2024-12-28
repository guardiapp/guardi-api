<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuildingRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorization handled by policies; allow if it reaches here.
        return true;
    }

    /**
     * Obtener las reglas de validación para la solicitud.
     *
     * @return array
     */
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

    /**
     * Mensajes de error personalizados.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'residence_id.required' => 'La residencia es obligatoria.',
            'residence_id.exists' => 'La residencia seleccionada no existe.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'floors_number.integer' => 'El número de pisos debe ser un valor entero.',
            'apartments_per_floor.integer' => 'El número de apartamentos por piso debe ser un valor entero.',
            'information.string' => 'La información debe ser una cadena de texto.',
            'active.boolean' => 'El estado activo debe ser un valor verdadero o falso.',
        ];
    }
}
