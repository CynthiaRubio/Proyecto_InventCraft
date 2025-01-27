<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActionBuildingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'building_id' => 'required|exists:buildings,id',
        ];

        /* TO DO Falta añadir esta regla
        foreach ($inventions as $typeId => $selectedInventions) {
            $rules["inventions.$typeId"] = "required|array|size:$level";
        }
        */
    }

    /**
     * Función para declarar los mensajes de los errores
     */
    public function messages(){
        return [
            'building_id.required' => 'Por favor, selecciona un edificio válido.',
            'building_id.exists' => 'El edificio seleccionado no existe en la base de datos.',
            'inventions.required' => 'Debes seleccionar al menos un invento para cada tipo.',
            'inventions.size' => 'Debes seleccionar exactamente $request->level inventos para cada tipo.',
        ];
    }
}
