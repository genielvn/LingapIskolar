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
        <div
            class="flex items-end justify-between border-b border-zinc-200 pb-6"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tight text-zinc-900 uppercase"
                >
                    My Tickets
                </h1>
                <p class="text-lg text-zinc-500">
                    Track and manage your support requests
                </p>
            </div>
            <div class="flex gap-4">
                <x-button
                    :variant="'secondary'"
                    onclick="location.reload()"
                    class="shadow-sm"
                >
                    <i class="bi bi-arrow-clockwise mr-2"></i>
                    Refresh
                </x-button>
                <x-button :href="route('ticket-create')">New Ticket</x-button>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <x-counter
                :name="'In Progress'"
                :value="1"
                class="border-l-4 border-l-amber-500 bg-white shadow-sm"
            />
            <x-counter
                :name="'Closed'"
                :value="1"
                class="border-l-4 border-l-red-600 bg-white shadow-sm"
            />
        </div>
        <div
            class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white p-4 shadow-sm"
        >
            <form
                method="GET"
                action="{{ route("dashboard") }}"
                class="flex w-full items-center gap-6"
            >
                <input
                    type="hidden"
                    name="search"
                    value="{{ request("search") }}"
                />

                <div class="flex flex-1 items-center gap-4">
                    <x-select-input :id="'status'" :label="'Filter Status'">
                        <option value="">All Statuses</option>
                        <option value="open">Open</option>
                        <option value="pending">Pending</option>
                        <option value="closed">Closed</option>
                    </x-select-input>

                    <x-select-input :id="'category'" :label="'Category'">
                        <option value="">All Levels</option>
                        <option value="urgent">Graduation</option>
                        <option value="high">Documents</option>
                    </x-select-input>
                </div>

                <div class="flex gap-2">
                    <x-button type="submit" class="min-w-32">
                        Apply Filters
                    </x-button>
                    @if (request()->anyFilled(["status", "category"]))
                        <a
                            href="{{ route("dashboard") }}"
                            class="flex items-center px-4 text-sm font-medium text-zinc-500 transition hover:text-red-800"
                        >
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div
            class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm"
        >
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr
                        class="bg-zinc-50 text-xs font-bold tracking-wider text-zinc-600 uppercase"
                    >
                        <th class="border-b border-zinc-200 px-6 py-4">
                            Ticket ID
                        </th>
                        <th class="border-b border-zinc-200 px-6 py-4">
                            Subject
                        </th>
                        <th class="border-b border-zinc-200 px-6 py-4">
                            Current Status
                        </th>
                        <th
                            class="border-b border-zinc-200 px-6 py-4 text-right"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($tickets as $ticket)
                        <tr class="group transition-all hover:bg-zinc-50/80">
                            <td class="px-6 py-5 align-top">
                                <span
                                    class="rounded bg-red-50 px-2 py-1 font-mono text-sm font-bold text-red-700"
                                >
                                    #{{ $ticket["id"] }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div
                                    class="font-bold text-zinc-900 transition-colors group-hover:text-red-800"
                                >
                                    {{ $ticket["subject"] }}
                                </div>
                                {{-- Truncated description helps keep the table tidy --}}
                                @if (isset($ticket["description"]))
                                    <div
                                        class="mt-1 max-w-sm truncate text-sm text-zinc-400"
                                    >
                                        {{ $ticket["description"] }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-5 align-top">
                                <x-ticket-status :status="$ticket['status']" />
                            </td>
                            <td class="px-6 py-5 text-right align-top">
                                <a
                                    href="/ticket/{{ $ticket["id"] }}"
                                    class="inline-flex items-center text-sm font-bold text-red-800 transition hover:text-red-600"
                                >
                                    View Thread
                                    <i class="bi bi-chevron-right ml-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i
                                        class="bi bi-ticket-perforated text-5xl text-zinc-300"
                                    ></i>
                                    <p
                                        class="mt-4 text-lg font-medium text-zinc-500"
                                    >
                                        You haven't created any tickets yet.
                                    </p>
                                    <x-button
                                        :href="route('ticket-create')"
                                        :variant="'secondary'"
                                        class="mt-4"
                                    >
                                        Create your first ticket
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
