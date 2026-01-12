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
                    </div>
                    <div
                        class="flex h-full w-full flex-1 flex-col rounded-2xl border-2 p-4"
                    >
                        <h2 class="mb-2 text-2xl font-bold">Reply Thread</h2>

                        <div class="flex-1 space-y-3 overflow-y-auto pr-2">
                            <div class="rounded-xl border p-3">
                                <p class="text-sm text-gray-500">
                                    User • 2026-01-12
                                </p>
                                <p>Message content here</p>
                            </div>

                            <div class="rounded-xl border bg-gray-50 p-3">
                                <p class="text-sm text-gray-500">
                                    Support • 2026-01-12
                                </p>
                                <p>Another message</p>
                            </div>
                        </div>

                        <form class="mt-4 flex w-full flex-row gap-2">
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
                    class="flex h-fit w-lg gap-2 self-start rounded-2xl border-2 p-4 flex-col"
                >
                    <h2 class="text-2xl font-bold">Assigned Agent</h2>
                    <div>
                        <img src="" alt="" class="h-[125px] w-[125px]" />
                        <p class="text-xl font-bold">Sample Name</p>
                        <p>Sample Title</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
