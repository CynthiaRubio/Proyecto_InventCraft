<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Zone;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\WithFaker;

class EventUnitTest extends TestCase
{
    use WithFaker;

    /**
     * Test para asegurarse de que se pueden crear eventos.
     */
    public function test_create_event()
    {
        $zone = Zone::create([
            'name' => 'Balcanes',
            'description' => 'Cordillera montañosa de difícil acceso',
        ]);

        $event = Event::create([
            'zone_id' => $zone->_id,
            'name' => 'Fiesta en la aldea',
            'description' => 'Un evento de celebración en la aldea.',
        ]);

        $this->assertDatabaseHas('events', [
            'name' => 'Fiesta en la aldea',
            'description' => 'Un evento de celebración en la aldea.',
            'zone_id' => $zone->_id,
        ]);

        $this->assertInstanceOf(Event::class, $event);
    }

    /**
     * Test para verificar que los campos del modelo son fillable.
     */
    public function test_fillable_properties()
    {
        $event = new Event();

        $fillable = ['zone_id', 'name', 'description'];

        $this->assertEquals($fillable, $event->getFillable());
    }
    
    /**
     * Test para comprobar la relación entre Evento y Zona
     */
    public function test_event_belongs_to_zone()
    {
        /* Creamos una zona */
        $zone = Zone::create([
            'name' => 'Balcanes',
            'description' => 'Cordillera montañosa de difícil acceso',
        ]);

        /* Creamos un evento asociado a la zona */
        $event = Event::create([
            'zone_id' => $zone->id,
            'name' => 'Avalancha',
            'description' => 'Desprendimiento de rocas',
        ]);

        /* Verificamos que el evento tiene la zona asociada */
        $this->assertEquals($zone->id, $event->zone->id);

        /* Verificamos que el evento tiene la zona asociada usando assertTrue */
        $this->assertTrue($event->zone->id === $zone->id);

        /* Verificamos que el evento tiene la relación con 'zone' correctamente asignada */
        $this->assertInstanceOf(Zone::class, $event->zone);
        $this->assertInstanceOf(BelongsTo::class, $event->zone());

        /* Verificamos que el evento tiene la zona asociada y que no tiene la zona equivocada usando assertFalse */
        $wrongZone = Zone::create([
            'name' => 'Cataratas',
            'description' => 'Páramo natural repleto de cataratas',
        ]);
        $this->assertFalse($event->zone->id === $wrongZone->id);
    }


    /**
     * Test para comprobar los eventos sin zona asociada
     */
    public function test_event_without_zone()
    {
        /* Creamos un evento sin zona asociada */
        $event = Event::create([
            'zone_id' => null,
            'name' => 'Evento sin zona',
            'description' => 'Descripción del Evento sin zona',
        ]);

        /* Verificamos que la zona asociada es null */
        $this->assertTrue(is_null($event->zone));
    }
}
/*
ADAPTAR TESTS A OTRO MODELO

-> Cambiar el modelo
-> AJUSTAR RELACIONES:
    Si el modelo tiene una relación belongsTo, revisa que la prueba test_model_belongs_to_relation() lo valide correctamente.
    Si en lugar de belongsTo tiene una relación hasMany, usa assertInstanceOf(HasMany::class, $modelo->relacion()).
-> Modificar los fillable por los campos del nuevo modelo
-> Si el modelo tiene otros atributos por ejemplo "efficiency" agregarlos en los test
*/