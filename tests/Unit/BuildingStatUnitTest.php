<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\BuildingStat;
use App\Models\Building;
use App\Models\Stat;

use Illuminate\Support\Facades\Artisan;

class BuildingStatUnitTest extends TestCase
{
    private $new_building;
    private $new_stat;
    private $new_building_2;
    private $new_stat_2;
    private $new_buildingStat;
    private $building;
    private $stat;
    private $buildingStat;

    /**
     * Función que prepara el entorno de las pruebas
     */
    protected function buildingStatSetUp()
    {

        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');

        /* Creamos nuevos edificios */
        $this->new_building = Building::create([
            'name' => 'Platillo volante',
            'description' => 'Nave espacial para cambiar de mundo',
            'coord_x' => 3,
            'coord_y' => 4,
        ]);

        $this->new_building_2 = Building::create([
            'name' => 'Torre effiel',
            'description' => 'Torre muy alta',
            'coord_x' => 4,
            'coord_y' => 1,
        ]);

        /* Creamos nuevas estadísticas */
        $this->new_stat = Stat::create([
            'name' => 'Ataque',
            'description' => 'Capacidad del usuario para atacar a otros jugadores',
        ]);

        $this->new_stat_2 = Stat::create([
            'name' => 'Agilidad',
            'description' => 'Capacidad del usuario para esquivar a otros jugadores',
        ]);

        /* Crear un BuildingStat asociando el edificio y la estadística */
        $this->new_buildingStat = BuildingStat::create([
            'building_id' => $this->new_building->id,
            'stat_id' => $this->new_stat_2->id,
            'value' => rand(0,100),
        ]);

        $this->new_buildingStat_2 = BuildingStat::create([
            'building_id' => $this->new_building_2->id,
            'stat_id' => $this->new_stat->id,
            'value' => rand(0,100),
        ]);

        /* Recogemos un edificio de la base de datos */
        $this->building = Building::all()->random();

        /* Recogemos una estadística de la base de datos */
        $this->stat = Stat::all()->random();

        /* Crear un BuildingStat asociando el edificio y la estadística recogidos */
        $this->buildingStat = BuildingStat::create([
            'building_id' => $this->building->id,
            'stat_id' => $this->stat->id,
            'value' => rand(0,100),
        ]);
        

    }

    /**
     * Test para comprobar la relación de BuildingStat con Building
     */
    public function test_building_stat_belongs_to_building()
    {
        $this->buildingStatSetUp();

        /* Verificamos que el BuildingStat tiene el edificio correspondiente asociado */
        $this->assertEquals($this->new_building->id, $this->new_buildingStat->building->id);
        $this->assertEquals($this->new_building_2->id, $this->new_buildingStat_2->building->id);
        $this->assertEquals($this->building->id, $this->buildingStat->building->id);
    }


    public function test_building_stat_belongs_to_stat()
    {
        $this->buildingStatSetUp();

        /* Verificamos que el BuildingStat tiene la estadística correspondiente asociada */
        $this->assertEquals($this->new_stat_2->id, $this->new_buildingStat->stat->id);
        $this->assertEquals($this->new_stat->id, $this->new_buildingStat_2->stat->id);
        $this->assertEquals($this->stat->id, $this->buildingStat->stat->id);
    }
}
