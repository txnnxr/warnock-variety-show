@extends('shows.layout')
@section('shows-content')
    <div class="card my-3">
        <div class="card-body">
            <h5 class="card-title">Thank you {{$invite->first_name}} {{$invite->middle_name}}
                {{$invite->last_name}}! Your response has been saved!</h5>
            <div class="form-control my-2">
                <div class="row">
                    <div class="col-12">Attending?</div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @if($invite->response_status == 'COWARD') MAYBE
                        @elseif($invite->response_status == 'WAITLIST') WAITLISTED
                        @else {{$invite->response_status}}
                        @endif
                        @if($invite->guest_request) - REQUESTED @endif
                        @if($invite->plus_one_status && in_array($invite->response_status, ['ATTENDING', 'WAITLIST'])) (+1) @endif
                    </div>
                </div>
            </div>
            @if($invite->response_status == 'WAITLIST')
                <div class="alert alert-warning my-2">
                    The show is full right now, so you're on the waitlist. If a seat opens up you'll move in automatically@if($invite->email) and we'll email you@endif.
                </div>
            @elseif($invite->response_status == 'ATTENDING' && $invite->guest_request)
                <div class="alert alert-info my-2">
                    Your request is waiting for approval. The address will show up here once you're approved@if($invite->email), and we'll email you@endif.
                </div>
            @endif
            @if($invite->canSeeAddress())
                <div class="form-control my-2">
                    <div class="row">
                        <div class="col-12">Where?</div>
                    </div>
                    <div class="row">
                        <div class="col-12">{{$show->address}}</div>
                    </div>
                </div>
            @endif
            @if($invite->response_status == "ATTENDING")
                <div class="talent-box form-control my-2">
                    <div class="row ">
                        <div class="col-12">Talent?</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if($invite->talent)
                                Yes
                            @else
                                No
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            <p class="my-2"><i>Bookmark this page to check on your RSVP later: <a href="{{ route('invites.thank-you', $invite) }}">{{ route('invites.thank-you', $invite) }}</a></i></p>
            <div class="row">
                @if($invite->canSeeAddress())
                    <div class="col">
                        <a href="{{ route('invites.calendar', $invite) }}" class="btn btn-primary"><i class="fa-solid fa-kiwi-bird"></i> Add
                            to Calendar
                        </a>
                    </div>
                @endif
                <div class="col">
                    <a href="{{ route('invites.respond', ['show' => $show, 'key' => $invite->key, 'change' => 1]) }}" class="btn btn-warning"><i class="fa-solid fa-hippo"></i>
                        Update Response</a>
                </div>
            </div>
        </div>
    </div>
@endsection
