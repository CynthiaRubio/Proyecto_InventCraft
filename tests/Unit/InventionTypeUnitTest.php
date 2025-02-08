<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\InventionType;
use App\Models\MaterialType;
use App\Models\Zone;
use App\Models\Building;
use App\Models\Invention;
use App\Models\InventionTypeInventionType;

use Illuminate\Support\Facades\Artisan;

class InventionTypeUnitTest extends TestCase
{
    private $inventionType;
    private $materialType;
    private $zone;
    private $building;
    private $carro;
    private $rueda;
    private $cesta;
    private $hacha;

    /**
     * Función que prepara el entorno de las pruebas
     */
    protected function inventionTypeSetUp(): void
    {
        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');
    
        /* Recuperamos de la base de datos un elemento de forma aleatoria */
        $this->materialType = MaterialType::all()->random();
        $this->zone = Zone::all()->random();
        $this->building = Building::all()->random();

        /* Creamos el tipo de invento */
        $this->inventionType = InventionType::create([
            'material_type_id' => $this->materialType->id,
            'zone_id' => $this->zone->id,
            'building_id' => $this->building->id,
            'name' => 'Prueba',
            'description' => 'Descripción del tipo de invento Prueba',
            'level_required' => 1,
        ]);

        /* Recuperamos la información de algunos tipos de inventos */
        $this->carro = InventionType::where('name' , 'Carro')->first();//->with('inventionTypesNeed')->get();
        $this->rueda = InventionType::where('name' , 'Rueda')->first();//->with('inventionTypesNeed')->get();
        $this->cesta = InventionType::where('name' , 'Cesta')->first();//->with('inventionTypesNeed')->get();
        $this->hacha = InventionType::where('name' , 'Hacha')->first();//->with('inventionTypesNeed')->get();
    }

    /**
     * Test que comprueba la relación InventionType-MaterialType
     */
    public function test_belongs_to_material_type()
    {
        $this->inventionTypeSetUp();

        /* Verificamos que InventionType tiene una relación con MaterialType */
        $this->assertInstanceOf(MaterialType::class, $this->inventionType->materialType);
        $this->assertEquals($this->materialType->id, $this->inventionType->materialType->id);
    }

    /**
     * Test que comprueba la relación InventionType-Zone
     */
    public function test_belongs_to_zone()
    {
        $this->inventionTypeSetUp();

        /* Verificamos que InventionType tiene una relación con Zone */
        $this->assertInstanceOf(Zone::class, $this->inventionType->zone);
        $this->assertEquals($this->zone->id, $this->inventionType->zone->id);
    }

    /**
     * Test que comprueba la relación InventionType-Building
     */
    public function test_belongs_to_building()
    {
        $this->inventionTypeSetUp();

        // Verificar que InventionType tiene una relación con Building
        $this->assertInstanceOf(Building::class, $this->inventionType->building);
        $this->assertEquals($this->building->id, $this->inventionType->building->id);
    }

    /**
     * Test que comprueba la relación InventionType-Invention
     */
    public function test_has_many_inventions()
    {
        $this->inventionTypeSetUp();

        // Crear una invención relacionada con InventionType
        $invention = Invention::create([
            'invention_type_id' => $this->inventionType->id,
            'name' => 'Test Invention',
            'efficiency' => 50,
            'available' => true,
        ]);

        // Verificar que el InventionType tiene muchas invenciones
        $this->assertInstanceOf(Invention::class, $this->inventionType->inventions->first());
        $this->assertEquals(1, $this->inventionType->inventions->count());
    }

    /**
     * Test que comprueba la relación InventionType-InventionType
     */
    // public function test_has_many_invention_types()
    // {
    //     $this->inventionTypeSetUp();

    //     // Verificar que InventionType tiene muchas InventionTypeInventionTypes (relación reflexiva)
    //     $this->assertInstanceOf(InventionType::class, $this->hacha->inventionTypes->first());
    // }

    /**
     * Test que comprueba la relación InventionType-InventionType
     */
    // public function test_has_many_invention_types_need()
    // {
    //     $this->inventionTypeSetUp();

    //     // Verificar que InventionType tiene muchas InventionTypeInventionTypes (relación reflexiva inversa)
    //     $this->assertInstanceOf(InventionType::class, $this->carro->inventionTypesNeed->first());
    // }

    /**
     * Test que comprueba si son correctas las relaciones reflexivas
     */
    public function test_has_required_inventions()
    {

        $carro = InventionType::create(['name' => 'Carro']);
        $rueda = InventionType::create(['name' => 'Rueda']);
        $cesta = InventionType::create(['name' => 'Cesta']);
        $hacha = InventionType::create(['name' => 'Hacha']);
        
        // //Asociar el carro con sus requerimientos
        $carro->inventionTypes()->create([
            'invention_type_need_id' => $rueda->_id,
            'quantity' => 2
        ]);
        
        $carro->inventionTypes()->create([
            'invention_type_need_id' => $cesta->_id,
            'quantity' => 1
        ]);
        
        $carro->inventionTypes()->create([
            'invention_type_need_id' => $hacha->_id,
            'quantity' => 1
        ]);

        // Verificar las relaciones
        $this->assertCount(3, $carro->inventionTypes);
        $this->assertTrue(
            $carro->inventionTypes->contains(function ($relation) {
                return $relation->inventionTypeNeed->name === 'Rueda' && $relation->quantity === 2;
            })
        );
        $this->assertTrue(
            $carro->inventionTypes->contains(function ($relation) {
                return $relation->inventionTypeNeed->name === 'Cesta' && $relation->quantity === 1;
            })
        );
        $this->assertTrue(
            $carro->inventionTypes->contains(function ($relation) {
                return $relation->inventionTypeNeed->name === 'Hacha' && $relation->quantity === 1;
            })
        );
    }

    /**
     * Test que comprueba si la relación entre dos tipos de inventos existe
     */
    public function test_create_a_related_invention_type()
    {

        $carro = InventionType::create(['name' => 'Carro']);
        $rueda = InventionType::create(['name' => 'Rueda']);
        $cesta = InventionType::create(['name' => 'Cesta']);
        $hacha = InventionType::create(['name' => 'Hacha']);

        // Crear una relación de necesidad entre el "Carro" y la "Rueda"
        $carro->inventionTypes()->create([
            'invention_type_need_id' => $rueda->id,
            'quantity' => 2
        ]);

        // Comprobar que la relación existe
        $inventionTypeRelation = InventionTypeInventionType::first();
        $this->assertNotNull($inventionTypeRelation);
        $this->assertEquals($carro->id, $inventionTypeRelation->invention_type_id);
        $this->assertEquals($rueda->id, $inventionTypeRelation->invention_type_need_id);
    }

    /**
     * Test que comprueba si la cantidad de inventos necesarios es correcta
     */
    public function test_has_correct_quantity_for_related_invention_type()
    {

        $carro = InventionType::create(['name' => 'Carro']);
        $rueda = InventionType::create(['name' => 'Rueda']);
        $cesta = InventionType::create(['name' => 'Cesta']);
        $hacha = InventionType::create(['name' => 'Hacha']);

        // Crear relación con cantidad 2
        $carro->inventionTypes()->create([
            'invention_type_need_id' => $rueda->id,
            'quantity' => 2
        ]);

        // Comprobar la cantidad
        $relation = $carro->inventionTypes->first();
        $this->assertEquals(2, $relation->quantity);
    }

    /**
     * Test que comprueba las múltiples relaciones
     */
    public function test_create_multiple_related_inventions()
    {

        $carro = InventionType::create(['name' => 'Carro']);
        $rueda = InventionType::create(['name' => 'Rueda']);
        $cesta = InventionType::create(['name' => 'Cesta']);
        $hacha = InventionType::create(['name' => 'Hacha']);

        // Crear múltiples relaciones
        $carro->inventionTypes()->createMany([
            [
                'invention_type_need_id' => $rueda->id,
                'quantity' => 2,
            ],
            [
                'invention_type_need_id' => $cesta->id,
                'quantity' => 1,
            ],
            [
                'invention_type_need_id' => $hacha->id,
                'quantity' => 1,
            ],
        ]);

        // Verificar que todas las relaciones se crearon
        $this->assertCount(3, $carro->inventionTypes);
        $this->assertTrue(
            $carro->inventionTypes->contains(function ($relation) {
                return $relation->inventionTypeNeed->name === 'Rueda' && $relation->quantity === 2;
            })
        );
        $this->assertTrue(
            $carro->inventionTypes->contains(function ($relation) {
                return $relation->inventionTypeNeed->name === 'Cesta' && $relation->quantity === 1;
            })
        );
        $this->assertTrue(
            $carro->inventionTypes->contains(function ($relation) {
                return $relation->inventionTypeNeed->name === 'Hacha' && $relation->quantity === 1;
            })
        );
    }

    /** @test */
    public function test_has_multiple_dependencies_for_ganaderia()
    {

        // Recuperar la información de los inventos necesarios para "Ganadería"
        $ganaderia = InventionType::create(['name' => 'Ganadería']);
        $cuerda = InventionType::create(['name' => 'Cuerda']);
        $piedraAfilada = InventionType::create(['name' => 'Piedra Afilada']);
        $trampa = InventionType::create(['name' => 'Trampa']);

        $ganaderia->inventionTypes()->create([
            'invention_type_need_id' => $cuerda->id,
            'quantity' => 3
        ]);
        $ganaderia->inventionTypes()->create([
            'invention_type_need_id' => $piedraAfilada->id,
            'quantity' => 3
        ]);
        $ganaderia->inventionTypes()->create([
            'invention_type_need_id' => $trampa->id,
            'quantity' => 1
        ]);

        // Verificar que "Ganadería" tiene las relaciones adecuadas
        $this->assertCount(3, $ganaderia->inventionTypes);
        $this->assertTrue(
            $ganaderia->inventionTypes->contains(function ($relation) {
                return $relation->inventionTypeNeed->name === 'Cuerda' && $relation->quantity === 3;
            })
        );
        $this->assertTrue(
            $ganaderia->inventionTypes->contains(function ($relation) {
                return $relation->inventionTypeNeed->name === 'Piedra Afilada' && $relation->quantity === 3;
            })
        );
        $this->assertTrue(
            $ganaderia->inventionTypes->contains(function ($relation) {
                return $relation->inventionTypeNeed->name === 'Trampa' && $relation->quantity === 1;
            })
        );
    }
}
