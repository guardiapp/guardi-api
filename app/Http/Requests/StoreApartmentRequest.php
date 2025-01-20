<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'name' => [ 'string', 'max:255'],
            'building_id' => ['required', 'exists:buildings,id'],
            'identifier' => ['required', 'string', 'max:20'],
            'document' => ['required', 'string', 'max:20'],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
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
            'identifier.required' => 'El apartamento es obligatorio.',
            'identifier.max' => 'El apartamento no puede superar los 20 caracteres.',
            'document.required' => 'El documento de identidad es obligatorio.',
            'document.max' => 'El documento de identidad no puede superar los 20 caracteres.',
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.max' => 'El nombre no puede superar los 50 caracteres.',
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.max' => 'El apellido no puede superar los 50 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no puede superar los 20 caracteres.',
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.mimes' => 'El avatar debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'avatar.max' => 'El avatar no puede ser mayor a 2 MB.',
        ];
    }
}
