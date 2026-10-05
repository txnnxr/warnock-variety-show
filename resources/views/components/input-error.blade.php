@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'list-unstyled text-danger small mt-1 mb-0']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
