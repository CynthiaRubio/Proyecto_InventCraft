<?php

namespace App\Http\Requests;

class MoveZoneRequest extends BaseRequest
{
    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> Array con las reglas de validación
     */
    public function rules(): array
    {
        return [
            'zone_id' => 'required|exists:zones,id',
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
            'zone_id.required' => 'Debes seleccionar una zona.',
            'zone_id.exists' => 'La zona seleccionada no existe en la base de datos.',
        ];
    }
}

