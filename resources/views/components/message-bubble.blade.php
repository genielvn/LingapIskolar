<div
    {{ $attributes->merge(["class" => "flex flex-col mb-6 " . ($me ? "items-end" : "items-start")]) }}
>
    <div class="mb-1 flex items-center gap-2 px-1">
        @if (! $me)
            <span class="text-xs font-semibold text-gray-600">
                {{ $name }}
            </span>
        @endif

        <span class="text-[10px] font-medium text-gray-400">{{ $date }}</span>
    </div>

    <div
        class="@if($me) flex-row-reverse @endif flex max-w-[85%] items-end gap-2"
    >
        <div class="flex-shrink-0">
            @if ($imgLink)
                <img
                    src="{{ $imgLink }}"
                    class="h-8 w-8 rounded-full border border-gray-100 object-cover shadow-sm"
                    alt="{{ $name }}"
                />
            @else
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-[10px] font-bold text-gray-500"
                >
                    {{ substr($name, 0, 1) }}
                </div>
            @endif
        </div>

        <div
            @class([
                "px-4 py-2 text-sm leading-relaxed shadow-sm transition-all",
                "rounded-2xl rounded-tr-none bg-indigo-600 text-white" => $me,
                "rounded-2xl rounded-tl-none border border-gray-200 bg-white text-gray-800" => ! $me,
            ])  
        >
            {{ $content }}
        </div>
    </div>
</div>
