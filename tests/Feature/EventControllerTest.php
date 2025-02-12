<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class EventControllerTest extends TestCase
{
    use WithFaker;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::create();
        $this->actingAs($this->user);

        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');
        
    }

    /**
     * Test para comprobar que se pueden listar eventos.
     */
    public function test_can_list_events()
    {

        $zone = Zone::all()->random();

        $event1 = Event::create([
            'zone_id' => $zone->_id,
            'name' => 'Event1',
        ]);

        $event2 = Event::create([
            'zone_id' => $zone->_id,
            'name' => 'Event2',
        ]);

        $event3 = Event::create([
            'zone_id' => $zone->_id,
            'name' => 'Event3',
        ]);

    
        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertViewHas('events');
    }

    /**
     * Test para comprobar que se puede acceder al formulario de creación de eventos.
     */
    public function test_can_access_create_event_form()
    {
        $response = $this->get(route('events.create'));

        $response->assertStatus(200);
        $response->assertViewHas('zones');
    }

    /**
     * Test para crear un evento.
     */
    public function test_can_create_event()
    {
        
        $zone = Zone::all()->random();

        
        $data = [
            'zone_id' => $zone->_id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
        
        $response = $this->post(route('events.store'), $data);

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events', $data);
    }

    /**
     * Test para ver un evento en detalle.
     */
    public function test_can_show_event()
    {
        $zone = Zone::all()->random();
        $event = Event::create(['zone_id' => $zone->_id]);

        $response = $this->get(route('events.show', $event->_id));

        $response->assertStatus(200);
        $response->assertViewHas('event', $event);
    }

    /**
     * Test para acceder al formulario de edición de eventos.
     */
    public function test_can_access_edit_event_form()
    {
        
        $zone = Zone::all()->random();
        $event = Event::create(['zone_id' => $zone->_id]);

        $response = $this->get(route('events.edit', $event->_id));

        $response->assertStatus(200);
        $response->assertViewHas(['event', 'zones']);
    }

    /**
     * Test para actualizar un evento existente.
     */
    public function test_can_update_event()
    {
        // 1. Crear un evento de prueba
        $zone = Zone::all()->random();
        $event = Event::create(['zone_id' => $zone->_id]);

        // 2. Definir los nuevos datos para actualizar el evento
        $updatedData = [
            'zone_id' => $zone->_id,
            'name' => 'Evento Actualizado',
            'description' => 'Descripción actualizada',
        ];

        $response = $this->put(route('events.update', $event->_id), $updatedData);

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events', $updatedData);
    }

    /**
     * Test para eliminar un evento.
     */
    public function test_can_delete_event()
    {
        $zone = Zone::all()->random();
        $event = Event::create(['zone_id' => $zone->_id]);

        $response = $this->delete(route('events.destroy', $event->_id));

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseMissing('events', ['_id' => $event->_id]);
    }


}











/*
ADAPTAR TESTS A OTRO CONTROLADOR

-> Cambiar el modelo  Event en todas las pruebas por el que sea (Building, Material....)
-> Asegurarse de que hay datos relacionados. Si el modelo necesita a otro necesitamos factorys, por ejemplo: Zone::factory()->create(). si no hay, crearlo como el de zone y event
-> Modificar rutas al del nuevo modelo: materials.indez, buildings.update....
-> Ajustar los datos de prueba al nuevo modelo ($data): Reemplaza 'name' => $this->faker->word etc por las columnas del nuevo modelo.
*/
