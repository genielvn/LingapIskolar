@extends("layouts.main")
@extends("layouts.agent-sidebar")

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
                <form
                    method="POST"
                    action="/ticket/{{ $ticket["id"] }}/status"
                    class="flex justify-center"
                >
                    @csrf
                    @method("PUT")
                    <input type="hidden" name="status" value="Escalated" />
                    <x-button
                        :variant="'primary'"
                        class="shadow-sm"
                        :type="'submit'"
                        :extend="false"
                    >
                        Escalate Ticket
                    </x-button>
                </form>
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
                <x-ticket-details-lifecycle :ticket="$ticket" />
            </div>
        </div>
    </div>
@endsection
