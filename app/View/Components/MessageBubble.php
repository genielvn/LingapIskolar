<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MessageBubble extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public string $date,
        public string $content,
        public string $imgLink,
        public bool $me,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("components.message-bubble");
    }

    public function getStyle(): string
    {
        $base = "rounded-xl border p-3";
        $match = $this->me ? "bg-white" : "bg-gray-100";
        return $base . " " . $match;
    }
}
