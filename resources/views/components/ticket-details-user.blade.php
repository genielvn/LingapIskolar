<div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-xs font-black tracking-widest text-zinc-500 uppercase">
        @if (auth()->user()->isUser())
            Assigned Agent
        @elseif (auth()->user()->isAgent() || $auth->user()->isManager())
            Requested By
        @endif
    </h2>
    <div class="flex flex-col items-center gap-4 text-center">
        <img
            src="/img/emu.jpg"
            alt="Agent"
            class="h-24 w-24 rounded-full border-4 border-white shadow-lg ring-1 ring-zinc-200"
        />

        @if (auth()->user()->isUser())
            <div>
                <p class="text-lg leading-tight font-black text-zinc-900">
                    {{ $ticket["assigned_to"] }}
                </p>
                <p class="text-sm font-medium text-zinc-500">
                    {{ $ticket["assignee_title"] }}
                </p>
            </div>
        @elseif (auth()->user()->isAgent() || $auth->user()->isManager())
            <div>
                <p class="text-lg leading-tight font-black text-zinc-900">
                    {{ $ticket["requested_by"] }}
                </p>
                <p class="text-sm font-medium text-zinc-500">
                    Student
                </p>
            </div>
        @endif
    </div>
</div>
