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
                        @if(!$invite->isOnAttendingWaitlist())
                            @if($invite->response_status == 'COWARD')
                                MAYBE
                            @else
                                {{$invite->response_status}}
                            @endif
                            @if($invite->guest_request)
                                - REQUESTED
                            @endif
                            <i>@if($invite->plus_one_status) +1 @if($invite->plus_one_name) ({{$invite->plus_one_name}}) @endif @endif</i></div>
                        @else
                            We're at max capacity. No more room at the swim club. You're on the waitlist to attend. We'll let you know if a spot opens up.
                        @endif
                </div>
            </div>
            @if($invite->response_status == "ATTENDING")
                <div class="talent-box form-control my-2">
                    <div class="row ">
                        <div class="col-12">Talent?</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if(!$invite->isOnTalentWaitlist())
                                @if($invite->talent)
                                    Yes
                                    @if(!empty($invite->talent_write_in))
                                        -
                                        <i>{{$invite->talent_write_in}}</i>
                                    @endif
                                @else
                                    No
                                @endif
                            @else
                                Sorry, jabroni. You're getting big leagued. You're on the waitlist to do a talent. We'll let you know if a spot opens up.
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
{{--                @if($invite->response_status == "ATTENDING")--}}
{{--                    <div class="col text-left">--}}
{{--                        <form method="post" action="/invites/{{$invite->id}}/generate-ics">--}}
{{--                            @csrf--}}
{{--                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-kiwi-bird"></i> Add--}}
{{--                                to Calendar--}}
{{--                            </button>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                @endif--}}
                <div class="col text-center">
                    <a href="/shows/{{$invite->show->id}}/view">
                        <button type="submit" class="btn btn-info"><i class="fa-solid fa-worm"></i>
                            View Show</button>
                    </a>
                </div>
                <div class="col text-end">
                    <a href="/invites/{{$invite->id}}/edit">
                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-hippo"></i>
                            Update Response</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
