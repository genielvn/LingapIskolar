<div
    class="flex flex-col gap-4 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm"
>
    <div>
        <h2
            class="mb-4 text-xs font-black tracking-widest text-zinc-500 uppercase"
        >
            Ticket Lifecycle
        </h2>
        <div class="flex flex-col gap-8">
            <div class="flex flex-col gap-2">
                <label
                    class="text-xs font-bold tracking-wider text-zinc-500 uppercase"
                >
                    Status
                </label>
                <form
                    class="flex w-full"
                    method="POST"
                    action="/ticket/{{ $ticket["id"] }}/status"
                >
                    @csrf
                    @method("PUT")
                    <input type="hidden" name="status" value="Resolved" />
                    <x-button
                        :height="'h-16'"
                        :variant="'green'"
                        :extend="true"
                        :type="'submit'"
                    >
                        Mark as Resolved
                    </x-button>
                </form>
                <div
                    class="flex w-full flex-row items-center justify-between gap-4"
                >
                    <div class="h-[2px] flex-1 rounded-2xl bg-zinc-500"></div>
                    <p class="font-bold text-zinc-500">OR</p>
                    <div class="h-[2px] flex-1 rounded-2xl bg-zinc-500"></div>
                </div>
                <form
                    method="POST"
                    action="/ticket/{{ $ticket["id"] }}/status"
                >
                    @csrf
                    @method("PUT")
                    <x-select-input
                        :id="'status'"
                        :value="$ticket['status']"
                        onchange="this.form.submit()"
                    >
                        <option value="Open">Open</option>
                        <option value="Assigned">Assigned</option>
                        <option value="Pending User Response">
                            Pending User Response
                        </option>
                        <option value="Closed">Closed</option>
                        <option value="Escalated">Escalated</option>
                        <option value="Resolved">Resolved</option>
                    </x-select-input>
                </form>
            </div>
            <form method="POST" action="/ticket/{{ $ticket["id"] }}/status">
                @csrf
                @method("PUT")

                <x-select-input
                    :id="'priority'"
                    :value="$ticket['priority']"
                    :label="'Priority'"
                    onchange="this.form.submit()"
                >
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                </x-select-input>
            </form>
        </div>
    </div>
</div>
