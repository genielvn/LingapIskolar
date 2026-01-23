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
                        Resolved Tickets
                    </h1>
                    <p class="text-lg text-zinc-500">
                        Check resolved tickets and whether delete them
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

        <x-ticket-table
            :columns="['id', 'requested_by', 'subject', 'assigned_to', 'status']"
            :tickets="$tickets"
            :agentButtonType="'delete'"
        ></x-ticket-table>
    </div>
@endsection
