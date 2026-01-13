@extends("layouts.main")

@section("headerside")
    <form
        method="GET"
        class="w-full max-w-[40%]"
        action="{{ route("dashboard") }}"
    >
        <x-text-input
            :id="'search'"
            :icon="'bi-search'"
            :value="request('search')"
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
                :color="'amber-500'"
            />
            <x-counter :name="'Closed'" :value="1" :color="'red-600'" />
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
        <x-ticket-table
            :columns="['id', '', 'subject', 'status']"
            :tickets="$tickets"
        />
    </div>
@endsection
