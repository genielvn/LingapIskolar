@extends("layouts.main")

@section("main")
    <div class="flex w-full flex-1 flex-col items-center justify-center">
        <div
            class="flex w-[90%] flex-1 flex-col gap-4 rounded-xl border-2 border-black bg-white p-8"
        >
            <div class="flex flex-row items-center gap-4">
                <h1 class="text-4xl font-bold">Ticket #{{ $ticket["id"] }}</h1>
                <x-ticket-status :status="$ticket['status']" />
            </div>

            <div class="flex h-full w-full flex-row gap-2">
                <div class="flex h-full w-full flex-col gap-2">
                    <div class="rounded-2xl border-2 p-4">
                        <h2 class="text-2xl font-bold">Details</h2>
                        <p>
                            <span class="font-bold">Subject:</span>
                            {{ $ticket["subject"] }}
                        </p>
                        <p>
                            <span class="font-bold">Description:</span>
                            {{ $ticket["description"] }}
                        </p>
                        <p>
                            <span class="font-bold">Category:</span>
                            {{ $ticket["category"] }}
                        </p>
                    </div>
                    <div
                        class="flex h-full w-full flex-1 flex-col rounded-2xl border-2 p-4"
                    >
                        <h2 class="mb-2 text-2xl font-bold">Reply Thread</h2>

                        <div class="flex-1 space-y-3 overflow-y-auto pr-2">
                            <x-message-bubble
                                :name="'User'"
                                :date="'2026-01-12'"
                                :content="'Message content here'"
                                :me="true"
                            />
                            <x-message-bubble
                                :name="'Support'"
                                :date="'2026-01-12'"
                                :content="'Another message'"
                                :me="false"
                            />
                        </div>

                        <form
                            method="POST"
                            class="mt-4 flex w-full flex-row gap-2"
                            action="/ticket/{{ $ticket["id"] }}/reply"
                        >
                            @csrf
                            <x-text-input
                                :id="'message'"
                                :label="'Message'"
                                :type="'text'"
                            />
                            <x-button :type="'submit'">Send</x-button>
                        </form>
                    </div>
                </div>
                <div
                    class="flex h-fit w-lg flex-col gap-2 self-start rounded-2xl border-2 p-4"
                >
                    <h2 class="text-2xl font-bold">Assigned Agent</h2>
                    <div>
                        <img
                            src="/img/emu.jpg"
                            alt=""
                            class="h-[125px] w-[125px]"
                        />
                        <p class="text-xl font-bold">Sample Name</p>
                        <p>Sample Title</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
