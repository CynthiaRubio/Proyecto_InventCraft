<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class InfoCard extends Component
{
    public function __construct(
        public string $title = '',
        public array $items = [],
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.info-card');
    }
}

