@extends("layouts.main")
@extends("layouts.manager-sidebar")

@section("headerside")
    <form
        method="GET"
        class="hidden w-full max-w-[40%] md:block"
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
    <div class="flex w-full flex-col gap-6 p-6 px-10">
        <x-page-header>
            <x-slot:header>
                <div>
                    <h1
                        class="text-3xl font-black tracking-tight text-zinc-900 uppercase"
                    >
                        Manager Dashboard
                    </h1>
                    <p class="text-lg text-zinc-500">
                        Assign tickets to agents and manage their tickets
                    </p>
                </div>
            </x-slot>
            <x-slot:side>
                <x-button
                    :variant="'secondary'"
                    class="shadow-sm hover:shadow"
                    onclick="location.reload()"
                >
                    <i class="bi bi-arrow-clockwise mr-2"></i>
                    Refresh
                </x-button>
            </x-slot>
        </x-page-header>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <x-counter
                :name="'Open'"
                :value="$stats['open']"
                :color="'border-l-green-600'"
            />
            <x-counter
                :name="'Active'"
                :value="$stats['active']"
                :color="'border-l-amber-500'"
            />
            <x-counter
                :name="'Escalated'"
                :value="$stats['escalated']"
                :color="'border-l-red-600'"
            />
            <x-counter
                :name="'Resolved'"
                :value="$stats['resolved']"
                :color="'border-l-zinc-400'"
            />
        </div>

        <x-filter :filters="['status', 'priority', 'category']" />

        <x-ticket-table
            :columns="['id', 'requested_by', 'subject', 'assigned_to', 'status', 'priority']"
            :tickets="$tickets"
            :agents="$agents"
        ></x-ticket-table>
    </div>
@endsection
