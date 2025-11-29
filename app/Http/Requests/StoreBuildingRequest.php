<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreBuildingRequest extends BaseRequest
{
    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> Array con las reglas de validación
     */
    public function rules(): array
    {
        $rules = [
            'building_id' => 'required|exists:buildings,id',
            'building_level' => 'required|integer|min:1',
            'inventions' => 'required|array',
        ];

        // Validar que cada tipo de invento tenga exactamente building_level inventos
        if ($this->has('inventions') && $this->has('building_level')) {
            foreach ($this->input('inventions', []) as $type_id => $inventions_selected) {
                $rules["inventions.$type_id"] = [
                    'required',
                    'array',
                    'size:' . $this->input('building_level'),
                ];
                $rules["inventions.$type_id.*"] = 'exists:inventions,id';
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
        $buildingLevel = $this->input('building_level', 'N');
        
        return [
            'building_id.required' => 'Por favor, selecciona un edificio válido.',
            'building_id.exists' => 'El edificio seleccionado no existe en la base de datos.',
            'building_level.required' => 'El nivel del edificio es requerido.',
            'building_level.integer' => 'El nivel del edificio debe ser un número entero.',
            'building_level.min' => 'El nivel del edificio debe ser al menos 1.',
            'inventions.required' => 'Debes seleccionar inventos para construir el edificio.',
            'inventions.array' => 'Los inventos deben ser un array.',
            'inventions.*.required' => 'Debes seleccionar al menos un invento para cada tipo.',
            'inventions.*.array' => 'Cada tipo de invento debe ser un array.',
            'inventions.*.size' => "Debes seleccionar exactamente $buildingLevel inventos para cada tipo.",
            'inventions.*.*.exists' => 'Uno o más inventos seleccionados no existen en la base de datos.',
        ];
    }
}

