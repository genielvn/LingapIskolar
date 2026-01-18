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
                        Manager List
                    </h1>
                    <p class="text-lg text-zinc-500">
                        Add users to manage and assign tickets to agents.
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
                <x-add-manager-modal />
            </x-slot>
        </x-page-header>

        <div
            class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white p-4 shadow-sm"
        >
            <form
                method="GET"
                action="{{ route("manager-list") }}"
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
                                href="{{ route("manager-list") }}"
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
                Managers
            </h2>
            <div
                class="flex flex-row flex-wrap items-center justify-evenly gap-2"
            >
                @foreach ($managers as $member)
                    <x-admin-member-list-card :member="$member" />
                @endforeach
            </div>
        </div>
    </div>
@endsection
