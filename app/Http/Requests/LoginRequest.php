<?php

namespace App\Http\Requests;

class LoginRequest extends BaseRequest
{
    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> Array con las reglas de validación
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
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
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Introduce una dirección de correo electrónico válida.',
            'email.exists' => 'Este correo electrónico no está registrado en el sistema.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }
}

