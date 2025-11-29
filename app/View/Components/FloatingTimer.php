<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FloatingTimer extends Component
{
    /**
     * Tiempo restante en segundos
     */
    public int $timeLeft;

    /**
     * Create a new component instance.
     */
    public function __construct(int $timeLeft)
    {
        $this->timeLeft = $timeLeft;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.floating-timer');
    }
}

