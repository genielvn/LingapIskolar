@extends("layouts.main")

@section("side")
    <form
        method="GET"
        class="w-full max-w-[40%]"
        action="{{ route("dashboard") }}"
    >
        <x-text-input
            :id="'search'"
            :icon="'bi-search'"
            :value="request('search')"
            :label="'Search'"
        />
    </form>
@endsection

@section("main")
    <div class="flex w-full flex-col gap-6 bg-zinc-50/50 p-6 px-10">
        <div class="flex items-end justify-between border-b border-zinc-200 pb-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-zinc-900 uppercase">
                    Agent Dashboard
                </h1>
                <p class="text-lg text-zinc-500">
                    Active tickets for <span class="font-semibold text-red-800">{{ auth()->user()->name }}</span>
                </p>
            </div>
            <x-button :variant="'secondary'" class="shadow-sm hover:shadow" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise mr-2"></i> Refresh
            </x-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-counter :name="'Overdue'" :value="1" class="border-l-4 border-l-red-600 shadow-sm bg-white" />
            <x-counter :name="'Unassigned'" :value="1" class="border-l-4 border-l-zinc-400 shadow-sm bg-white" />
            <x-counter :name="'Escalated'" :value="1" class="border-l-4 border-l-amber-500 shadow-sm bg-white" />
            <x-counter :name="'Resolved'" :value="1" class="border-l-4 border-l-green-600 shadow-sm bg-white" />
        </div>

        <div class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('dashboard') }}" class="flex w-full items-center gap-6">
                <input type="hidden" name="search" value="{{ request('search') }}">
                
                <div class="flex flex-1 items-center gap-4">
                    <x-labeled-select-input :id="'status'" :label="'Filter Status'" class="w-full max-w-xs">
                        <option value="">All Statuses</option>
                        <option value="open">Open</option>
                        <option value="pending">Pending</option>
                        <option value="closed">Closed</option>
                    </x-labeled-select-input>

                    <x-labeled-select-input :id="'priority'" :label="'Priority Level'" class="w-full max-w-xs">
                        <option value="">All Levels</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High</option>
                    </x-labeled-select-input>
                </div>

                <div class="flex gap-2">
                    <x-button type="submit" class="min-w-32">Apply Filters</x-button>
                    @if(request()->anyFilled(['status', 'priority']))
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 text-sm font-medium text-zinc-500 hover:text-red-800 transition">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50 text-xs font-bold uppercase tracking-wider text-zinc-600">
                        <th class="px-6 py-4 border-b border-zinc-200">Ticket ID</th>
                        <th class="px-6 py-4 border-b border-zinc-200">Subject & Description</th>
                        <th class="px-6 py-4 border-b border-zinc-200">Category</th>
                        <th class="px-6 py-4 border-b border-zinc-200">Status</th>
                        <th class="px-6 py-4 border-b border-zinc-200 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($tickets as $ticket)
                        <tr class="group transition-all hover:bg-zinc-50/80">
                            <td class="px-6 py-5 align-top">
                                <span class="rounded bg-red-50 px-2 py-1 font-mono text-sm font-bold text-red-700">
                                    #{{ $ticket["id"] }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-zinc-900 group-hover:text-red-800 transition-colors">
                                    {{ $ticket["subject"] }}
                                </div>
                                <div class="mt-1 max-w-sm truncate text-sm text-zinc-500">
                                    {{ $ticket["description"] }}
                                </div>
                            </td>
                            <td class="px-6 py-5 align-top">
                                <span class="inline-flex items-center rounded-md bg-zinc-100 px-2.5 py-0.5 text-xs font-semibold text-zinc-700 ring-1 ring-inset ring-zinc-200">
                                    {{ $ticket["category"] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 align-top">
                                <x-ticket-status :status="$ticket['status']" />
                            </td>
                            <td class="px-6 py-5 text-right align-top">
                                <a href="#" class="inline-flex items-center text-sm font-bold text-red-800 hover:text-red-600 transition">
                                    Manage <i class="bi bi-chevron-right ml-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-inbox text-5xl text-zinc-300"></i>
                                    <p class="mt-4 text-lg font-medium text-zinc-500">No tickets found</p>
                                    <p class="text-sm text-zinc-400">Try adjusting your filters or search query.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
