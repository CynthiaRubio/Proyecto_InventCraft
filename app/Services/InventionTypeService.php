<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\InventionTypeServiceInterface;
use App\Models\InventionType;
use App\Models\InventionTypeInventionType;
use Illuminate\Database\Eloquent\Collection;

class InventionTypeService implements InventionTypeServiceInterface
{
    /**
     * Obtiene el tipo de invento por su ID
     * 
     * @param string $id ID del tipo de invento
     * @return InventionType|null Tipo de invento o null si no existe
     */
    public function getInventionType(string $id): ?InventionType
    {
        $invention_type = InventionType::find($id);
        return $invention_type;
    }

    /**
     * Obtiene el tipo de invento con su tipo de material precargado
     * 
     * @param string $id ID del tipo de invento
     * @return InventionType|null Tipo de invento con relación materialType o null si no existe
     */
    public function getInventionTypeWithRelations(string $id): ?InventionType
    {
        $invention_type = InventionType::with('materialType')->find($id);
        return $invention_type;
    }

    /**
     * Obtiene los tipos de inventos necesarios para crear un tipo de invento específico
     * 
     * @param string $invention_type_id ID del tipo de invento padre
     * @return \Illuminate\Database\Eloquent\Collection Colección de tipos de inventos requeridos con sus cantidades
     */
    public function getInventionsNeeded(string $invention_type_id): Collection
    {
        $invention_types_needed = InventionTypeInventionType::where('invention_type_id', $invention_type_id)
                    ->with('inventionTypeNeed')->get();

        return $invention_types_needed;

    }


    /**
     * Obtiene los tipos de inventos necesarios en formato de array asociativo
     * Formato: [['nombre_tipo' => cantidad], ...]
     * 
     * @param string $invention_type_id ID del tipo de invento padre
     * @return array|null Array de tipos de inventos requeridos o null si no hay requerimientos
     */
    public function beforeGetInventionsNeeded(string $invention_type_id): ?array
    {
        $required_inventions = [];

        $invention_types_needed = InventionTypeInventionType::where('invention_type_id', $invention_type_id)
                    ->with('inventionTypeNeed')->get();

        if ($invention_types_needed->isNotEmpty()) {
            foreach ($invention_types_needed as $invention_type) {
                $attributes_invention_type = InventionType::find($invention_type->invention_type_need_id);
                if ($attributes_invention_type) {
                    $required_inventions[] = [
                        $attributes_invention_type->name => $invention_type->quantity,
                    ];
                }
            }
        }

        if ($required_inventions) {
            return $required_inventions;
        }

        return null;
    }
}
