<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends BaseRequest
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
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> Array con las reglas de validación
     */
    public function rules(): array
    {
        return [
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'loss_percent' => 'required|numeric|min:0|max:100',
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
            'zone_id.required' => 'Por favor, selecciona una zona válida.',
            'zone_id.exists' => 'La zona que has seleccionada no existe.',
            'name.required' => 'Por favor, indica un nombre para tu evento',
            'name.string' => 'Por favor, el nombre del evento tiene que ser un string',
        ];
    }
}

