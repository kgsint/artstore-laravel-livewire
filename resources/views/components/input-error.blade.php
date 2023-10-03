@props(['message'])

@if ($message)
    <div {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
            {{ $message }}
    </div>
@endif
