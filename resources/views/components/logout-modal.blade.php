<div x-data="{ show: false }">
    <x-button @click="show = true" :extend="true">Log Out</x-button>

    <template x-teleport="body">
        <div
            x-show="show"
            x-transition.opacity
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <div
                @click.away="show = false"
                class="mx-4 flex w-full max-w-md flex-col gap-4 rounded-2xl border border-zinc-200 bg-white p-6 shadow-xl"
            >
                <div>
                    <h1
                        class="text-3xl font-black tracking-tight text-zinc-900 uppercase"
                    >
                        Log Out
                    </h1>
                </div>

                <p class="text-zinc-600">
                    You will need to log in again to access your account.
                </p>

                <form
                    action="/logout"
                    method="POST"
                    class="mt-4 flex w-full flex-col gap-3 md:flex-row-reverse"
                >
                    @csrf
                    <x-button type="submit" class="w-full">
                        Confirm Log Out
                    </x-button>

                    <x-button
                        type="button"
                        :variant="'secondary'"
                        @click="show = false"
                        class="w-full"
                    >
                        Cancel
                    </x-button>
                </form>
            </div>
        </div>
    </template>
</div>
