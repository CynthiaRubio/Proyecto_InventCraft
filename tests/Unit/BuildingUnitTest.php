<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Building;
use App\Models\InventionType;
use App\Models\ActionBuilding;
use App\Models\BuildingStat;
use App\Models\Stat;


use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;

class BuildingUnitTest extends TestCase
{
    use WithoutMiddleware;

    private $building;

    /**
     * Función que prepara el entorno de las pruebas
     */
    protected function buildingSetUp(){

        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');

        /* Seleccionamos un edificio de forma aleatoria */
        $buildings = Building::all();
        $this->building = $buildings->random();
    }

    /**
     * Test que comprueba la relación Building-InventionType
     */
    public function test_building_has_invention_types()
    {
        
        $this->buildingSetUp();

        /* Recuperamos los tipos de inventos asociados a ese edificio */
        $inventionTypes = InventionType::where('building_id' , $this->building->id)->get();

        /* Comprobamos que los tipos de inventos son los que tocan */
        foreach($inventionTypes as $inventionType){
            $this->assertTrue($this->building->inventionTypes->contains($inventionType));
        }
    }

    /**
     * Test que comprueba la relación Building-Action mediante ActionBuilding
     */
    public function test_building_has_action_building()
    {
        $this->buildingSetUp();

        /* Creamos un ActionBuilding relacionado con el edificio */
        $actionBuilding = ActionBuilding::create([
            'action_id' => 1,
            'building_id' => $this->building->id,
            'efficiency' => 10,
            'available' => true,
            ]);

        /* Verificamos que el edificio tiene el ActionBuilding asociado */
        $this->assertTrue($this->building->actions->contains($actionBuilding));
    }


    /**
     * Test que comprueba la relación Building-Stat existentes
     */
    public function test_building_has_building_stats_existents()
    {
        $this->buildingSetUp();

        /* Recuperamos las estadísticas asociadas a ese edificio */
        $buildingStats = BuildingStat::where('building_id' , $this->building->_id)->get();

        /* Comprobamos que los tipos de inventos son los que tocan */
        foreach($buildingStats as $stat){
            $this->assertTrue($this->building->stats->contains($stat));
        }
    }


    /**
     * Test que comprueba la correcta asignación de estadisticas al edificio
     */
    public function test_building_has_building_stats_added()
    {
        $this->buildingSetUp();

        /* Creamos estadísticas asociadas al edificio */
        $buildingStat1 = BuildingStat::create([
            'building_id' => $this->building->id, 
            'stat_id' => 1, 
            'value' => 100,
        ]);
        $buildingStat2 = BuildingStat::create([
            'building_id' => $this->building->id, 
            'stat_id' => 2, 
            'value' => 50,
        ]);

        /* Verificamos que el edificio tiene las nuevas estadísticas relacionadas */
        $this->assertTrue($this->building->stats->contains($buildingStat1));
        $this->assertTrue($this->building->stats->contains($buildingStat2));
    }
}
