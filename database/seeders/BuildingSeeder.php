<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$buildings = include database_path('data/buildings.php');

        $buildings = [
            ['name' => 'Estación de Transporte', 'description' => 'Permite el transporte de recursos y personas entre distintas zonas', 'coord_x' => 1, 'coord_y' => 1],
            ['name' => 'Taller de Manufactura', 'description' => 'Espacio dedicado a la fabricación y ensamblaje de herramientas y productos', 'coord_x' => 1, 'coord_y' => 2],
            ['name' => 'Granja', 'description' => 'Produce alimentos y recursos agrícolas para la comunidad', 'coord_x' => 1, 'coord_y' => 3],
            ['name' => 'Planta de Energía', 'description' => 'Genera energía necesaria para mantener las operaciones y construcciones', 'coord_x' => 2, 'coord_y' => 1],
            ['name' => 'Fundición de Metales', 'description' => 'Procesa minerales para obtener metales útiles para la construcción y herramientas', 'coord_x' => 2, 'coord_y' => 2],
            ['name' => 'Taller de Cerámica', 'description' => 'Fabrica productos de cerámica útiles para almacenamiento y decoración', 'coord_x' => 2, 'coord_y' => 3],
            ['name' => 'Fábrica de Textiles', 'description' => 'Produce telas y prendas de ropa para diferentes necesidades', 'coord_x' => 3, 'coord_y' => 1],
            ['name' => 'Sistemas de Acueductos', 'description' => 'Distribuye agua a las zonas y edificios para el consumo y producción', 'coord_x' => 3, 'coord_y' => 2],
            ['name' => 'Estación Espacial', 'description' => 'Base avanzada para investigaciones y viajes espaciales', 'coord_x' => 3, 'coord_y' => 3],
        ];

        foreach($buildings as $building){
            Building::create($building);
        }
    }
}
