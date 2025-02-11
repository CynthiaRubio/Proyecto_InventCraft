<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Zone;

use App\Services\ActionManagementService;
use App\Services\FreeSoundService;

use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;

class ZoneControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function test_shows_all_zones()
    {
        
        $zone1 = Zone::create([
            'name' => 'Zona de prueba',
        ]);

        $zone2 = Zone::create([
            'name' => 'Zona de prueba 2',
        ]);


        /* Realizamos la petición a la ruta del index */
        $response = $this->get(route('zones.index'));

        /* Verificamos que la vista contiene las zonas creadas */
        $response->assertSuccessful()->assertSee('Mapa');
        $response->assertViewHas('zones', function ($zones) use ($zone1, $zone2) {
            return $zones->contains($zone1) && $zones->contains($zone2);
        });
    }

    // public function it_shows_zone_with_related_data_and_sound()
    // {
    //     // Crear una zona
    //     $zone = Zone::factory()->create();

    //     // Crear un mock del servicio FreeSoundService
    //     $freeSoundServiceMock = Mockery::mock(FreeSoundService::class);
    //     $freeSoundServiceMock->shouldReceive('getSoundUrl')
    //                          ->once()
    //                          ->andReturn('https://example.com/sound.mp3');

    //     // Crear un mock del servicio ActionManagementService
    //     $actionManagementServiceMock = Mockery::mock(ActionManagementService::class);
    //     $actionManagementServiceMock->shouldReceive('calculateMoveTime')
    //                                 ->once()
    //                                 ->andReturn(10);

    //     // Inyectar los mocks en el controlador
    //     $this->app->instance(FreeSoundService::class, $freeSoundServiceMock);
    //     $this->app->instance(ActionManagementService::class, $actionManagementServiceMock);

    //     // Simular la solicitud GET
    //     $response = $this->get(route('zones.show', ['zone' => $zone->id]));

    //     // Verificar que la respuesta tiene el código correcto
    //     $response->assertStatus(200);

    //     // Verificar que la vista contiene la zona y los datos del sonido
    //     $response->assertViewHas('zone', $zone);
    //     $response->assertViewHas('sound_url', 'https://example.com/sound.mp3');
    //     $response->assertViewHas('moveTime', 10);
    // }

    /** @test */
    // public function it_shows_zone_with_session_sound()
    // {
    //     // Crear una zona
    //     $zone = Zone::factory()->create();

    //     // Simular que ya se ha guardado un sonido en la sesión
    //     Session::put('zonesound' . $zone->id, 'https://example.com/existing-sound.mp3');

    //     // Realizar la solicitud GET
    //     $response = $this->get(route('zones.show', ['zone' => $zone->id]));

    //     // Verificar que la respuesta tiene el código correcto
    //     //$response->assertStatus(200);

    //     // Verificar que se utiliza el sonido de la sesión
    //     $response->assertViewHas('sound_url', 'https://example.com/existing-sound.mp3');
    // }
}
