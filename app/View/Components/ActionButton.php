<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ActionButton extends Component
{
    public function __construct(
        public string $href = '#',
        public string $text = 'Acción',
        public string $variant = 'primary',
        public string $size = 'lg',
        public bool $fullWidth = false,
        public ?string $icon = null,
    ) {}

    /**
     * Get the button classes
     */
    public function buttonClasses(): string
    {
        $classes = "btn btn-{$this->variant} btn-{$this->size} shadow fw-bold";
        
        if ($this->fullWidth) {
            $classes .= ' w-100';
        }
        
        return $classes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.action-button');
    }
}

