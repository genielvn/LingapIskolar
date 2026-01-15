<div
    class="max-h-48 overflow-y-auto rounded-lg border border-zinc-200 bg-white p-2 shadow-sm"
>
    <div class="flex flex-col gap-1">
        @foreach ($agents as $agent)
            <label
                class="flex cursor-pointer items-center justify-between rounded-md px-3 py-2 transition-colors hover:bg-zinc-100"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-200 text-xs font-bold text-zinc-600"
                    >
                        {{ substr($agent["name"], 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-800">
                            {{ $agent["name"] }}
                        </p>
                        <p class="text-sm font-medium text-zinc-500">
                            {{ $agent["email"] }}
                        </p>
                    </div>
                </div>

                <input
                    type="radio"
                    name="assigned_to"
                    value="{{ $agent["email"] }}"
                    class="h-4 w-4 border-zinc-300 text-blue-600 focus:ring-blue-500"
                />
            </label>
        @endforeach
    </div>
</div>
