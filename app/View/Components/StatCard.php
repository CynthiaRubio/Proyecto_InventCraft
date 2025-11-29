<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class StatCard extends Component
{
    public function __construct(
        public string $name = '',
        public ?string $description = null,
        public int $value = 0,
        public string $gradient = 'linear-gradient(145deg, #6c63ff, #00bcd4)',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.stat-card');
    }
}

