<?php

namespace App\Http\Requests;

class ChangeAvatarRequest extends BaseRequest
{
    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> Array con las reglas de validación
     */
    public function rules(): array
    {
        return [
            'avatar' => 'required|integer|min:1|max:6',
        ];
    }

    /**
     * Obtiene los mensajes personalizados para los errores de validación.
     * 
     * @return array<string, string> Array con los mensajes de error personalizados
     */
    public function messages(): array
    {
        return [
            'avatar.required' => 'Debes seleccionar un avatar.',
            'avatar.integer' => 'El avatar debe ser un número.',
            'avatar.min' => 'El avatar debe ser al menos 1.',
            'avatar.max' => 'El avatar no puede ser mayor que 6.',
        ];
    }
}

