<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TicketStatus extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $status) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("components.ticket-status");
    }
    public function getStyle(): string
    {
        $base =
            "w-64 items-center justify-center rounded-2xl p-4 text-center font-bold text-white";
        $colors = match ($this->status) {
            "Open" => "bg-green-600",
            "Assigned" => "bg-green-600",
            "In Progress" => "bg-yellow-600",
            "Pending User Response" => "bg-yellow-600",
            "Escalated" => "bg-green-600",
            "Resolved" => "bg-green-900",
            "Closed" => "bg-red-900",
        };
        return $base . " " . $colors;
    }
}
