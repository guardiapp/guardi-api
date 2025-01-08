<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResidentRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'building_id' => ['required', 'exists:buildings,id'],
            'document' => ['required', 'string', 'max:20'],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'apartment' => ['required', 'string', 'max:20']
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
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'building_id.required' => 'El edicicio es obligatorio.',
            'building_id.exists' => 'El edicicio seleccionado no existe.',
            'document.required' => 'El documento de identidad es obligatorio.',
            'document.max' => 'El documento de identidad no puede superar los 20 caracteres.',
            'document.unique' => 'El documento de identidad ya está registrado.',
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.max' => 'El nombre no puede superar los 50 caracteres.',
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.max' => 'El apellido no puede superar los 50 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
            'apartment.required' => 'El apartamento es obligatorio.',
            'apartment.max' => 'El apartamento no puede superar los 20 caracteres.',
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.mimes' => 'El avatar debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'avatar.max' => 'El avatar no puede ser mayor a 2 MB.',
        ];
    }

    /**
     * Preparar los datos para la validación.
     */
    protected function prepareForValidation()
    {
        if ($this->isJson() && is_null($this->request->get('building_id'))) {
            // Transforma los datos JSON para asegurarte de que se usen correctamente.
            $this->merge($this->json()->all());
        }
    }

    /**
     * Acción cuando falla la autorización.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new \Illuminate\Auth\Access\AuthorizationException(
            __('No estás autorizado para realizar esta acción.')
        );
    }


}
