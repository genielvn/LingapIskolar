<div class="w-full max-w-sm" x-data="{ show: false }">
    <div
        class="flex w-full max-w-sm flex-row items-center gap-4 rounded-2xl bg-white p-6 shadow-sm transition-all hover:shadow-md"
        @click="show = true"
    >
        <img
            src="{{ $member["img_link"] }}"
            class="h-24 w-24 shrink-0 rounded-full border-4 border-white shadow-lg ring-1 ring-zinc-200"
        />
        <div class="min-w-0">
            <p
                class="truncate text-xl font-bold text-zinc-800"
                title="{{ $member["name"] }}"
            >
                {{ $member["name"] }}
            </p>
            <p
                class="truncate text-sm font-medium text-zinc-500"
                title="{{ $member["email"] }}"
            >
                {{ $member["email"] }}
            </p>
        </div>
    </div>
    <template x-teleport="body">
        <div
            x-show="show"
            x-transition.opacity
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <form method="POST" action="/manager/revoke">
                @csrf
                @method("PUT")
                <div
                    class="flex flex-col gap-4 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm"
                >
                    <div>
                        <x-page-header>
                            <x-slot:header>
                                <div>
                                    <h1
                                        class="text-3xl font-black tracking-tight text-zinc-900 uppercase"
                                    >
                                        Details
                                    </h1>
                                </div>
                            </x-slot>
                            <x-slot:side></x-slot>
                        </x-page-header>
                    </div>
                    <input
                        type="hidden"
                        value="{{ $member["email"] }}"
                        name="email"
                        id="email"
                    />
                    <div class="flex items-center gap-4">
                        <img
                            src="{{ $member["img_link"] }}"
                            class="h-24 w-24 shrink-0 rounded-full border-4 border-white shadow-lg ring-1 ring-zinc-200"
                        />
                        <div class="min-w-0">
                            <p
                                class="truncate text-xl font-bold text-zinc-800"
                                title="{{ $member["name"] }}"
                            >
                                {{ $member["name"] }}
                            </p>
                            <p
                                class="truncate text-sm font-medium text-zinc-500"
                                title="{{ $member["email"] }}"
                            >
                                {{ $member["email"] }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-4 flex w-full flex-col items-center justify-center gap-4 md:flex-row"
                    >
                        <x-button :variant="'secondary'" @click="show = false">
                            Close
                        </x-button>
                        <x-button :type="'submit'">Revoke</x-button>
                    </div>
                </div>
            </form>
        </div>
    </template>
</div>
