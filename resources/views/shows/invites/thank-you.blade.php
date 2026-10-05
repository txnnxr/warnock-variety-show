@extends('shows.layout')
@section('shows-content')
    <div class="card">
        <div class="card-body">
            <h2 class="section-heading">Thank you, {{$invite->first_name}}!</h2>
            <p class="text-center">Your response has been saved.</p>

            @if($invite->response_status == 'WAITLIST')
                <div class="alert alert-warning">
                    The show is full right now, so you're on the waitlist. If a seat opens up you'll move in automatically{{ $invite->email ? " and we'll email you" : '' }}.
                </div>
            @elseif($invite->response_status == 'ATTENDING' && $invite->guest_request)
                <div class="alert alert-info">
                    Your request is waiting for approval. The address will show up here once you're approved{{ $invite->email ? ", and we'll email you" : '' }}.
                </div>
            @endif

            <dl class="row mb-0">
                <dt class="col-sm-4 form-label">Attending?</dt>
                <dd class="col-sm-8">
                    <x-rsvp-status :invite="$invite" />
                    @if($invite->plus_one_status && in_array($invite->response_status, ['ATTENDING', 'WAITLIST'])) <span class="text-muted">(+1)</span> @endif
                </dd>
                @if($invite->response_status == "ATTENDING")
                    <dt class="col-sm-4 form-label">Talent?</dt>
                    <dd class="col-sm-8">{{ $invite->talent ? 'Yes' : 'No' }}</dd>
                @endif
                @if($invite->canSeeAddress())
                    <dt class="col-sm-4 form-label">Where?</dt>
                    <dd class="col-sm-8">{{$show->address}}</dd>
                @endif
            </dl>

            <div class="btn-group-actions mt-4">
                @if($invite->canSeeAddress())
                    <a href="{{ route('invites.calendar', $invite) }}" class="btn btn-primary"><i class="fa-solid fa-kiwi-bird"></i> Add to Calendar</a>
                @endif
                <a href="{{ route('invites.respond', ['show' => $show, 'key' => $invite->key, 'change' => 1]) }}" class="btn btn-outline-secondary"><i class="fa-solid fa-hippo"></i> Update Response</a>
            </div>

            <p class="small text-muted mt-4 mb-0">Bookmark this page to check on your RSVP later: <a href="{{ route('invites.thank-you', $invite) }}" class="text-break">{{ route('invites.thank-you', $invite) }}</a></p>
        </div>
    </div>
@endsection
