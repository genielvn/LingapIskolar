<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TicketPriority extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $priority) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("components.ticket-priority");
    }
    public function getStyle(): string
    {
        $base =
            "inline-flex items-center justify-center rounded-full px-4 py-1.5 my-1 text-xs font-black uppercase tracking-widest border min-w-[120px] shadow-sm";

        $colors = match (strtolower($this->priority)) {
            "urgent" => "bg-red-600 text-white border-red-700 shadow-red-200",
            "high" => "bg-rose-50 text-rose-700 border-rose-200",
            "medium" => "bg-amber-50 text-amber-700 border-amber-200",
            "low" => "bg-slate-50 text-slate-600 border-slate-200",

            default => "bg-zinc-50 text-zinc-500 border-zinc-200",
        };

        return "{$base} {$colors}";
    }
}
