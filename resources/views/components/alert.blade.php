<div
    {{
        $attributes->merge([
            "class" => $getStyle(),
        ])
    }}
>
    <div>
        <i
            {{
                $attributes->merge([
                    "class" => $getIcon(),
                ])
            }}
        ></i>
    </div>
    <div>
        <p class="font-bold">{{ $title }}</p>
        <div>
            {{ $slot }}
        </div>
    </div>
</div>