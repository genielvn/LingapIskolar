<div {{ $attributes->merge(["class" => $getStyle()]) }}>
    <p class="text-sm text-gray-500">{{ $name }} • {{ $date }}</p>
    <p>{{ $content }}</p>
</div>
