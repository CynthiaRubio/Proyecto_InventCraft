<?php

namespace App\Http\Requests;

class UpdateUserStatsRequest extends BaseRequest
{
    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> Array con las reglas de validación
     */
    public function rules(): array
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'stats' => 'required|array',
        ];

        // Validar cada stat individualmente
        // El key del array es el stat_id, el value es el incremento
        if ($this->has('stats')) {
            foreach (array_keys($this->input('stats', [])) as $statId) {
                // Validar que el stat_id existe
                $rules["stats.$statId"] = [
                    'required',
                    'integer',
                    'min:0',
                ];
            }
        }

        return $rules;
    }

    /**
     * Obtiene los mensajes personalizados para los errores de validación.
     * 
     * @return array<string, string> Array con los mensajes de error personalizados
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'El ID del usuario es requerido.',
            'user_id.exists' => 'El usuario no existe en la base de datos.',
            'stats.required' => 'Debes asignar puntos a las estadísticas.',
            'stats.array' => 'Las estadísticas deben ser un array.',
            'stats.*.required' => 'Cada estadística debe tener un valor.',
            'stats.*.integer' => 'El valor de la estadística debe ser un número entero.',
            'stats.*.min' => 'El valor de la estadística no puede ser negativo.',
            'stats.*.exists' => 'Una o más estadísticas no existen en la base de datos.',
        ];
    }
}

