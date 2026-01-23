@extends("layouts.main")
@extends("layouts.admin-sidebar")

@section("main")
    <div class="flex w-full flex-col gap-6 p-6 px-10">
        <x-page-header>
            <x-slot:header>
                <div>
                    <h1
                        class="text-3xl font-black tracking-tight text-zinc-900 uppercase"
                    >
                        Admin Dashboard
                    </h1>
                    <p class="text-lg text-zinc-500"></p>
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
        <div
            class="flex w-full flex-col gap-8 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm md:flex-row"
        >
            <div class="flex flex-1 flex-col">
                <h2
                    class="text-s mb-4 font-black tracking-widest text-zinc-500 uppercase"
                >
                    Tickets Overview
                </h2>
                <div class="flex flex-col gap-4">
                    <x-counter
                        :name="'Total'"
                        :value="$stats['total']"
                        :color="'border-l-orange-200'"
                        :icon="'bi-ticket-detailed'"
                    />
                    <x-counter
                        :name="'Open'"
                        :value="$stats['open']"
                        :icon="'bi-door-open'"
                        :color="'border-l-green-600'"
                    />
                    <x-counter
                        :name="'Resolved'"
                        :value="$stats['closed']"
                        :color="'border-l-gray-200'"
                        :icon="'bi-door-closed'"
                    />
                </div>
            </div>
            <div class="flex flex-1 flex-col">
                <h2
                    class="text-s mb-4 font-black tracking-widest text-zinc-500 uppercase"
                >
                    Assignees Overview
                </h2>
                <div class="flex flex-col gap-4">
                    <x-counter
                        :name="'Assigners Total'"
                        :value="$stats['agents_total']"
                        :color="'border-l-orange-200'"
                        :icon="'bi-person-add'"
                    />
                    <x-counter
                        :name="'Manager Total'"
                        :value="$stats['managers_total']"
                        :color="'border-l-cyan-200'"
                        :icon="'bi-person-up'"
                    />
                    <x-counter
                        :name="'Users Total'"
                        :value="$stats['inactive']"
                        :color="'border-l-gray-200'"
                        :icon="'bi-person-x'"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
