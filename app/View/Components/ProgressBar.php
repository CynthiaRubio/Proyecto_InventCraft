<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ProgressBar extends Component
{
    public function __construct(
        public int $value = 0,
        public int $max = 100,
        public string $label = '',
        public string $color = 'primary',
        public string $height = '20px',
        public bool $showValue = true,
        public bool $animated = false,
        public bool $striped = false,
    ) {}

    /**
     * Calculate the percentage
     */
    public function percentage(): float
    {
        return min(100, max(0, ($this->value / $this->max) * 100));
    }

    /**
     * Get the background color class
     */
    public function bgClass(): string
    {
        return match($this->color) {
            'success' => 'bg-success',
            'warning' => 'bg-warning',
            'danger' => 'bg-danger',
            'info' => 'bg-info',
            default => 'bg-primary',
        };
    }

    /**
     * Get all CSS classes for the progress bar
     */
    public function progressBarClasses(): string
    {
        $classes = $this->bgClass();
        
        if ($this->striped) {
            $classes .= ' progress-bar-striped';
        }
        
        if ($this->animated) {
            $classes .= ' progress-bar-animated';
        }
        
        return $classes;
    }

    /**
     * Get the display value
     */
    public function displayValue(): string
    {
        if (!$this->showValue) {
            return '';
        }
        
        return $this->label ? (string)$this->value : $this->percentage() . '%';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.progress-bar');
    }
}

