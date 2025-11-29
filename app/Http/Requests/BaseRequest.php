<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     * 
     * @return bool True si está autorizado, false en caso contrario
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene los mensajes personalizados para los errores de validación.
     * 
     * @return array<string, string> Array con los mensajes de error personalizados
     */
    public function messages(): array
    {
        return [];
    }
}

