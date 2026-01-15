<div x-data="{ show: false }">
    <x-button @click="show = true">Easy Assign</x-button>

    <template x-teleport="body">
        <div
            x-show="show"
            x-transition.opacity
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <form method="POST" action="/ticket/{{ $ticket["id"] }}/assign">
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
                                        Ticket Assignment
                                    </h1>
                                    <p class="text-lg text-zinc-500">
                                        Please select an agent to assign this
                                        ticket.
                                    </p>
                                </div>
                            </x-slot>
                            <x-slot:side></x-slot>
                        </x-page-header>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label
                            class="ml-1 text-xs font-bold tracking-wider text-zinc-500 uppercase"
                        >
                            Agent to be assigned
                        </label>

                        <x-agent-select-input
                            :agents='$agents'
                        />
                    </div>
                    <x-select-input :id="'priority'" label="Priority">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </x-select-input>
                    <div
                        class="mt-4 flex w-full flex-col items-center justify-center gap-4 md:flex-row"
                    >
                        <x-button :variant="'secondary'" @click="show = false">
                            Close
                        </x-button>
                        <x-button :type="'submit'">Assign</x-button>
                    </div>
                </div>
            </form>
        </div>
    </template>
</div>
