<?php

namespace App\Services;

use App\Models\InventionType;
use App\Models\InventionTypeInventionType;

class InventionTypeService
{
    /**
     * Función que determina el tipo de inventos necesarios para ese tipo de inventos
     *
     * @param $invention_type_id: Id del tipo de inventos padre
     */
    public function getInventionsNeeded(string $invention_type_id)
    {
        $required_inventions = [];

        $invention_types_needed = InventionTypeInventionType::where('invention_type_id', $invention_type_id)->get();

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
