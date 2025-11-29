<?php

namespace App\Http\Requests;

class StoreInventionRequest extends BaseRequest
{
    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> Array con las reglas de validación
     */
    public function rules(): array
    {
        return [
            'invention_type_id' => 'required|exists:invention_types,id',
            'material_id' => 'required|exists:materials,id',
            'time' => 'required|integer|min:30|max:600',
            'inventions' => 'nullable|array',
            'inventions.*' => 'array',
            'inventions.*.*' => 'exists:inventions,id',
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
            'invention_type_id.required' => 'Debes seleccionar un tipo de invento.',
            'invention_type_id.exists' => 'El tipo de invento seleccionado no existe en la base de datos.',
            'material_id.required' => 'Debes seleccionar un material.',
            'material_id.exists' => 'El material seleccionado no existe en la base de datos.',
            'time.required' => 'Debes especificar el tiempo dedicado a la creación del invento.',
            'time.integer' => 'El tiempo debe ser un número entero.',
            'time.min' => 'El tiempo mínimo es de 30 minutos.',
            'time.max' => 'El tiempo máximo es de 600 minutos.',
            'inventions.array' => 'Los inventos deben ser un array.',
            'inventions.*.array' => 'Cada tipo de invento debe ser un array.',
            'inventions.*.*.exists' => 'Uno o más inventos seleccionados no existen en la base de datos.',
        ];
    }
}

