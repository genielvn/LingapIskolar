<div class="relative">
    @if ($icon)
        <i
            class="bi {{ $icon }} absolute top-1/2 left-4 -translate-y-1/2 text-4xl text-neutral-800"
        ></i>
    @endif

    <select
        {{
            $attributes->merge([
                "class" => $getStyleOfInput(),
                "id" => $id,
                "name" => $id,
                "value" => $value,
            ])
        }}
    >
        {{ $slot }}
    </select>
</div>
