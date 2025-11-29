<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SectionHeader extends Component
{
    public function __construct(
        public string $title = '',
        public string $icon = '',
        public string $bgColor = '#3498db',
        public string $size = '2.5rem',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.section-header');
    }
}

