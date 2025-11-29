<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PageTitle extends Component
{
    public function __construct(
        public string $title = '',
        public string $gradient = 'linear-gradient(45deg, #ff6f61, #ff9a8b)',
        public string $borderColor = '#ff6f61',
        public string $size = '3rem',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.page-title');
    }
}

