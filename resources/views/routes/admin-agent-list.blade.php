@extends("layouts.main")
@extends("layouts.admin-sidebar")

@section("main")

    @if (session('success'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show"
            x-transition.opacity
            class="px-8 py-4"
        >
            <x-alert 
                title="Success! The operation is successful!" 
                :icon="'bi-check-circle-fill'" 
                type="success" 
                :message="session('success')" 
            />
        </div>
    @endif

    @if (session('error'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show"
            x-transition.opacity
            class="px-8 py-4"
        >
            <x-alert 
                title="Error! The operation encounters an error." 
                :icon="'bi-x-circle'" 
                type="danger" 
                :message="session('error')" 
            />
        </div>
    @endif


    <div class="flex w-full flex-col gap-6 p-6 px-10">
        <x-page-header>
            <x-slot:header>
                <div>
                    <h1
                        class="text-3xl font-black tracking-tight text-zinc-900 uppercase"
                    >
                        Agent List
                    </h1>
                    <p class="text-lg text-zinc-500">
                        Add users to handle assigned tickets.
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
                <x-add-agent-modal />
            </x-slot>
        </x-page-header>

        <div
            class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white p-4 shadow-sm"
        >
            <form
                method="GET"
                action="{{ route("agent-list") }}"
                class="flex w-full items-center gap-6"
            >
                <div
                    class="flex flex-1 flex-col items-center gap-4 md:flex-row"
                >
                    <div class="w-full">
                        <x-text-input
                            :id="'search'"
                            :icon="'bi-search'"
                            :value="request('search')"
                            :label="'Search'"
                        />
                    </div>

                    <div class="flex flex-col items-center gap-2 md:flex-row">
                        <x-button type="submit" class="min-w-32">
                            Apply Filters
                        </x-button>
                        @if (request()->anyFilled(["search", "status", "priority"]))
                            <a
                                href="{{ route("agent-list") }}"
                                class="flex items-center px-4 text-sm font-medium text-zinc-500 transition hover:text-red-800"
                            >
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div
            class="flex flex-col rounded-xl border border-zinc-200 bg-white p-4 shadow-sm"
        >
            <h2
                class="text-s mb-4 font-black tracking-widest text-zinc-500 uppercase"
            >
                Agents
            </h2>
            <div
                class="flex flex-row flex-wrap items-center justify-evenly gap-2"
            >
                @foreach ($agents as $agent)
                    <x-admin-member-list-card :member="$agent" />
                @endforeach
            </div>
        </div>
    </div>
@endsection
