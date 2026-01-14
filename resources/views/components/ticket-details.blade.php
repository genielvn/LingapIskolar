<div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-xs font-black tracking-widest text-zinc-500 uppercase">
        Ticket Overview
    </h2>
    <div class="flex flex-col gap-4">
        @if (in_array("subject", $columns))
            <div class="col-span-2">
                <p class="text-xs font-bold text-zinc-400 uppercase">Subject</p>
                <p class="text-lg font-bold text-zinc-900">
                    {{ $ticket["subject"] }}
                </p>
            </div>
        @endif

        @if (in_array("category", $columns))
            <div>
                <p class="text-xs font-bold text-zinc-400 uppercase">
                    Category
                </p>
                <span
                    class="inline-flex items-center rounded-md bg-zinc-100 px-2.5 py-0.5 text-xs font-semibold text-zinc-700 ring-1 ring-zinc-200 ring-inset"
                >
                    {{ $ticket["category"] }}
                </span>
            </div>
        @endif

        @if (in_array("priority", $columns))
            <div>
                <p class="text-xs font-bold text-zinc-400 uppercase">
                    Priority
                </p>
                <x-ticket-priority :priority="$ticket['priority']" />
            </div>
        @endif

        @if (in_array("description", $columns))
            <div class="rounded-xl border border-zinc-100 bg-zinc-50 p-4">
                <p class="mb-2 text-xs font-bold text-zinc-400 uppercase">
                    Issue Description
                </p>
                <p class="leading-relaxed text-zinc-700">
                    {{ $ticket["description"] }}
                </p>
            </div>
        @endif
    </div>
</div>
