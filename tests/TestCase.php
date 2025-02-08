<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp(); // Asegurar que Laravel se inicializa correctamente

        // Vaciar la base de datos MongoDB antes de cada test
        DB::connection('mongodb')->getMongoClient()
            ->dropDatabase(config('database.connections.mongodb.database'));
    }
}