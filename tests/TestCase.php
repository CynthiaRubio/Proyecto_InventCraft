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

        // Migrar la base de datos antes de cada test
        $this->artisan('migrate:fresh');
    }
}