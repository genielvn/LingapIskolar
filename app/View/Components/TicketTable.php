<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TicketTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $columns,
        public $tickets,
        public ?array $agents = [],
        public ?string $agentButtonType,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view("components.ticket-table");
    }

    public function getRowStyle(?string $assigned)
    {
        $base = "group transition-all hover:bg-zinc-50/80";
        if (!auth()->user()->isManager()) {
            return $base;
        }

        if ($assigned == "Unassigned") {
            return "group transition-all hover:bg-red-100/80 bg-red-100";
        }
        return $base;
    }
}
