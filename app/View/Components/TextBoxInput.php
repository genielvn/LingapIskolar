<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextBoxInput extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $icon = null,
        public ?string $label = null,
        public ?string $value = null,
        public string $id,
        public string $type = "text",
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("components.text-box-input");
    }

    public function getStyleOfInput(): string
    {
        $base =
            "w-full rounded-4xl border-2 border-amber-400 bg-white p-4 outline-none";
        if ($this->icon) {
            return $base . " " . "pl-16";
        }
        return $base;
    }
}
