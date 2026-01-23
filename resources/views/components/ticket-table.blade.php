<div
    class="overflow-x overflow-scroll rounded-xl border border-zinc-200 bg-white shadow-sm"
>
    <table class="w-full border-collapse text-left">
        <thead>
            <tr
                class="bg-zinc-50 text-xs font-bold tracking-wider text-zinc-600 uppercase"
            >
                @if (in_array("id", $columns))
                    <th class="border-b border-zinc-200 px-6 py-4">
                        Ticket ID
                    </th>
                @endif

                @if (in_array("requested_by", $columns))
                    <th class="border-b border-zinc-200 px-6 py-4">
                        Requester
                    </th>
                @endif

                @if (in_array("assigned_to", $columns))
                    <th class="border-b border-zinc-200 px-6 py-4">
                        Assigned Agent
                    </th>
                @endif

                @if (in_array("subject", $columns))
                    <th class="border-b border-zinc-200 px-6 py-4">
                        Subject & Description
                    </th>
                @endif

                @if (in_array("status", $columns))
                    <th class="border-b border-zinc-200 px-6 py-4">Status</th>
                @endif

                @if (in_array("priority", $columns))
                    <th class="border-b border-zinc-200 px-6 py-4">Priority</th>
                @endif

                <th class="border-b border-zinc-200 px-6 py-4 text-right">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
            @forelse ($tickets as $ticket)
                <tr
                    {{ $attributes->merge(["class" => $getRowStyle($ticket["assigned_to"])]) }}
                >
                    @if (in_array("id", $columns))
                        <td class="px-6 py-5">
                            <span
                                class="rounded bg-red-50 px-2 py-1 font-mono text-sm font-bold text-red-700"
                            >
                                #{{ $ticket["id"] }}
                            </span>
                        </td>
                    @endif

                    @if (in_array("requested_by", $columns))
                        <td class="px-6 py-5">
                            {{ $ticket["requested_by"] }}
                        </td>
                    @endif

                    @if (in_array("assigned_to", $columns))
                        <td class="px-6 py-5">
                            {{ $ticket["assigned_to"] }}
                        </td>
                    @endif

                    @if (in_array("subject", $columns))
                        <td class="px-6 py-5">
                            <div
                                class="font-bold text-zinc-900 transition-colors group-hover:text-red-800"
                            >
                                {{ $ticket["subject"] }}
                            </div>
                            <div
                                class="mt-1 max-w-sm truncate text-sm text-zinc-500"
                            >
                                {{ $ticket["description"] }}
                            </div>
                        </td>
                    @endif

                    @if (in_array("status", $columns))
                        <td class="px-6 py-5">
                            <x-ticket-status :status="$ticket['status']" />
                        </td>
                    @endif

                    @if (in_array("priority", $columns))
                        <td class="px-6 py-5">
                            <x-ticket-priority
                                :priority="$ticket['priority']"
                            />
                        </td>
                    @endif

                    <td
                        class="flex flex-col items-end gap-4 px-6 py-5 align-top"
                    >
                        <div>
                            <x-button :href="'/ticket/' . $ticket['id']">
                                View Ticket
                            </x-button>
                        </div>

                        @if (Auth()->user()->isManager())
                            @if ($agentButtonType === "delete")
                                <form action="/ticket/delete" method="POST">
                                    @method("DELETE")
                                    @csrf
                                    <input
                                        type="hidden"
                                        value="{{ $ticket["id"] }}"
                                        name="id"
                                    />
                                    <x-button :type="'submit'">
                                        Delete
                                    </x-button>
                                </form>
                            @else
                                <x-assign-agent-modal
                                    :ticket="$ticket"
                                    :agents="$agents"
                                />
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <i class="bi bi-inbox text-5xl text-zinc-300"></i>
                            <p class="mt-4 text-lg font-medium text-zinc-500">
                                No tickets found
                            </p>
                            <p class="text-sm text-zinc-400">
                                Try adjusting your filters or search query.
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
