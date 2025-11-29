<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AccordionItem extends Component
{
    public function __construct(
        public string $id = '',
        public string $title = '',
        public ?int $count = null,
        public int $index = 0,
        public string $type = 'invention',
    ) {}

    /**
     * Get the collapse ID
     */
    public function collapseId(): string
    {
        return $this->type === 'invention' 
            ? "collapseInvention{$this->index}" 
            : "collapseMaterial{$this->index}";
    }

    /**
     * Get the target class for Bootstrap collapse
     */
    public function targetClass(): string
    {
        return $this->type === 'invention' 
            ? ".collapseInvention{$this->index}" 
            : ".collapseMaterial{$this->index}";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.accordion-item');
    }
}

