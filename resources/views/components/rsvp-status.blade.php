@props(['invite'])

@php
    [$label, $class] = match (true) {
        $invite->response_status === 'ATTENDING' => ['Attending', 'status-attending'],
        $invite->response_status === 'COWARD' => ['Maybe', 'status-maybe'],
        $invite->response_status === 'NO' => ['Not coming', 'status-no'],
        $invite->response_status === 'WAITLIST' => ['Waitlist', 'status-waitlist'],
        $invite->response_status === 'CREATED' => ['Not sent', 'status-no'],
        $invite->response_status === 'PENDING - SENT' => ['Sent', 'status-pending'],
        $invite->response_status === 'PENDING - OPENED' => ['Opened', 'status-pending'],
        str_starts_with((string) $invite->response_status, 'PENDING') => ['Pending', 'status-pending'],
        default => [ucwords(strtolower((string) $invite->response_status)), 'status-no'],
    };
@endphp

<span {{ $attributes->merge(['class' => "status {$class}"]) }}>{{ $label }}</span>
@if($invite->guest_request)
    <span class="status status-requested">Requested</span>
@endif
