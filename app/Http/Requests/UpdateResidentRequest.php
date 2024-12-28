<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResidentRequest extends FormRequest
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
        $rules = [
            'building_id' => ['required', 'exists:buildings,id'],
            'apartment' => ['required', 'string', 'max:20'],
            'document' => ['required', 'string', 'max:20'],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:20'],
        ];

        if ($this->hasFile('avatar')) {
            $rules['avatar'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
        } else {
            $rules['avatar'] = ['nullable', 'string'];
        }

        return $rules;
    }

    /**
     * Mensajes de error personalizados.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'building_id.required' => 'El edificio es obligatorio.',
            'building_id.exists' => 'El edificio seleccionada no existe.',
            'document.required' => 'El documento de identidad es obligatorio.',
            'document.max' => 'El documento de identidad no puede superar los 20 caracteres.',
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.max' => 'El nombre no puede superar los 50 caracteres.',
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.max' => 'El apellido no puede superar los 50 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no puede superar los 50 caracteres.',
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.mimes' => 'El avatar debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'avatar.max' => 'El avatar no puede ser mayor a 2 MB.',
        ];
    }
}
