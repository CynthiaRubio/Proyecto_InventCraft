<?php

namespace App\Services;

use App\Models\InventionType;
use App\Models\InventionTypeInventionType;

class InventionTypeService
{
    /**
     * Obtiene el tipo de invento por su id
     */
    public function getInventionType(string $id)
    {
        $invention_type = InventionType::find($id);
        return $invention_type;
    }

    /**
     * Obtiene el tipo de invento y su tipo de material por su id
     */
    public function getInventionTypeWithRelations(string $id)
    {
        $invention_type = InventionType::with('materialType')->find($id);
        return $invention_type;
    }


    /**
     * Determina el tipo de inventos necesarios para el id de un tipo de inventos
     *
     * @param $invention_type_id: Id del tipo de inventos padre
     */
    public function getInventionsNeeded(string $invention_type_id)
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

    }
}
