<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitorRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Las policies gestionan la autorización.
    }

    /**
     * Reglas de validación para el StoreVisitorRequest.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'resident_id' => ['required', 'exists:residents,id'],
            'document' => ['required', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'active' => ['boolean'],
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'resident_id.required' => 'El ID del residente es obligatorio.',
            'resident_id.exists' => 'El residente seleccionado no existe.',
            'document.required' => 'El documento es obligatorio.',
            'document.string' => 'El documento debe ser una cadena.',
            'document.max' => 'El documento no puede tener más de 50 caracteres.',
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.string' => 'El nombre debe ser una cadena.',
            'first_name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.string' => 'El apellido debe ser una cadena.',
            'last_name.max' => 'El apellido no puede tener más de 255 caracteres.',
            'active.boolean' => 'El estado activo debe ser verdadero o falso.',
        ];
    }
}
