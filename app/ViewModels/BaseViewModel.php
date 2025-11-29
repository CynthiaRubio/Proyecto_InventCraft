<?php

namespace App\ViewModels;

abstract class BaseViewModel
{
    /**
     * Convierte el ViewModel a un array para usar en las vistas
     *
     * @return array Array con las propiedades del ViewModel
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

