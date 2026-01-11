<div class="relative">
    @if ($icon)
        <i
            class="bi {{ $icon }} absolute top-1/2 left-4 -translate-y-1/2 text-4xl text-neutral-800"
        ></i>
    @endif

    <textarea
        {{
            $attributes->merge([
                "class" => $getStyleOfInput(),
                "id" => $id,
                "placeholder" => $label,
                "name" => $id,
                "value" => $value,
            ])
        }}
    ></textarea>
</div>
