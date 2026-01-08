@props(["href" => null, "type" => "button"])


@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(["class" => "inline-flex items-center px-4 py-2 min-w-48 bg-red-800 justify-center text-center rounded-full border border-transparent font-semibold text-white hover:bg-red-700 active:bg-red-950 transition ease-in-out duration-150"]) }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(["class" => "inline-flex items-center px-4 py-2 min-w-48 bg-red-800 justify-center text-center rounded-full border border-transparent font-semibold text-white hover:bg-red-700 active:bg-red-950 transition ease-in-out duration-150"]) }}
    >
        {{ $slot }}
    </button>
@endif
    