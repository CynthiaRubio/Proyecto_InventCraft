<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MaterialControllerTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Test que comprueba los materiales de la base de datos
     */
    public function test_all_materials(){
        /* Rellenamos la nueva base de datos con todos los materiales */
        Artisan::call('migrate');

        $materials = $this->get('/materials/index');
        $materials->assertNotNull($materials);

    }

}
