<div
    class="flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm"
>
    <div class="border-b border-zinc-200 bg-zinc-50 px-6 py-4">
        <h2 class="text-xs font-black tracking-widest text-zinc-500 uppercase">
            Reply Thread
        </h2>
    </div>

    <div class="h-[500px] space-y-4 overflow-y-auto bg-zinc-50/30 p-6">
        @foreach ($chat as $message)
            <x-message-bubble
                :name="$message['name']"
                :date="$message['date']"
                :content="$message['message']"
                :me="$message['me']"
                :img-link="$message['img_link']"
            />
        @endforeach
    </div>

    <div class="border-t border-zinc-200 bg-white p-6">
        <form
            method="POST"
            class="flex flex-col gap-3"
            action="/ticket/{{ $id }}/reply"
        >
            @csrf
            <x-text-box-input
                :id="'message'"
                :placeholder="'Type your message here...'"
            />
            <div class="flex justify-end">
                <x-button :type="'submit'" class="min-w-32 shadow-md">
                    Send Reply
                    <i class="bi bi-send ml-2 text-xs"></i>
                </x-button>
            </div>
        </form>
    </div>
</div>
