<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Zone;

class EventUnitTest extends TestCase
{
    
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
