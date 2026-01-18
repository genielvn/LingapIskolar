@extends("layouts.main")
@extends("layouts.manager-sidebar")

@section("main")
    <div class="flex w-full flex-col gap-6 p-6 px-10">
        <x-page-header>
            <x-slot:header>
                <h1
                    class="text-3xl font-black tracking-tight text-zinc-900 uppercase"
                >
                    Ticket
                    <span class="text-red-800">#{{ $ticket["id"] }}</span>
                </h1>
                <x-ticket-status :status="$ticket['status']" />
            </x-slot>
            <x-slot:side>
                <x-button
                    :variant="'secondary'"
                    :href="route('dashboard')"
                    class="shadow-sm"
                >
                    <i class="bi bi-arrow-left mr-2"></i>
                    Back to Dashboard
                </x-button>
            </x-slot>
        </x-page-header>
        <div class="flex flex-col items-start gap-8 md:flex-row">
            <div class="flex w-full flex-1 flex-col gap-6">
                <x-ticket-details
                    :ticket="$ticket"
                    :columns="['subject', 'description', 'category', 'priority']"
                />

                <x-chat :chat="$chat" :id="$ticket['id']" />
            </div>

            <div class="flex w-full flex-col gap-6 md:w-80">
                <x-ticket-details-user :ticket="$ticket" :user="'user'" />
                <x-ticket-details-user :ticket="$ticket" :user="'agent'" />
                <div
                    class="flex flex-col rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm"
                >
                    <h2
                        class="mb-4 text-xs font-black tracking-widest text-zinc-500 uppercase"
                    >
                        Agent Assign
                    </h2>
                    <form
                        method="POST"
                        action="/ticket/{{ $ticket["id"] }}/assign"
                        class="flex flex-col gap-4"
                    >
                        @csrf
                        @method("PUT")

                        <x-agent-select-input :agents="$agents" />

                        <x-button :type="'submit'">Assign</x-button>
                    </form>
                </div>
                <x-ticket-details-lifecycle :ticket="$ticket" />
            </div>
        </div>
    </div>
@endsection
