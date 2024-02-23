@extends('shows.layout')
@section('shows-content')
    @if($show->date > Carbon\Carbon::today())
        <div class="card card-alt mt-3">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 my-1">
                        <a href="/shows/{{$show->id}}/submission-applications/create" class="btn btn-info form-control">Exhibition Application</a>
                    </div>
                    <div class="col-md-6 my-1">
                        <button class="form-control btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#rsvpModal">RSVP</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card card-alt-2 my-3">
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-sm-3">
                    <h3 class="headers">Attending ({{count($show->attending_invites) + count($show->attending_invites_with_plus_one)}})</h3>

                    <ul class="inviteeList">
                        @foreach($show->attending_invites as $invite)

                            <li><i class="fa-regular fa-circle-check"></i> @if($invite->talent)
                                    <i class="fa-solid fa-otter"></i>
                                @else
                                    <i class="fa-solid fa-bugs"></i>
                                @endif {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}} @if($invite->plus_one_status) (+1) @endif</li>
                        @endforeach
                    </ul>
                </div>
                @if(count($show->maybe_invites))
                    <div class="col-6 col-sm-3">
                        <h3>Maybe ({{count($show->maybe_invites)}})</h3>
                        <ul class="inviteeList">
                            @foreach($show->maybe_invites as $invite)
                                <li><i class="fa-regular fa-circle-question"></i> @if($invite->talent)
                                        <i class="fa-solid fa-otter"></i>
                                    @else
                                        <i class="fa-solid fa-bugs"></i>
                                    @endif  {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(count($show->submissionApplications))
                    <div class="col-6 col-sm-3">
                        <h3 class="headers">Exhibitions ({{$show->getApplicationsWithStatus(true)->count()}})</h3>

                        <ul class="inviteeList">
                            @foreach($show->getApplicationsWithStatus(true) as $application)
                                <li>
                                    @if(auth()->user())
                                        <a href="/shows/{{$show->id}}/submission-applications/{{$application->id}}/view">@endif{{$application->name}} - {{$application->title}}@if(auth()->user())</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if($show->getApplicationsWithStatus(false)->count())
                        <div class="col-6 col-sm-3">
                            <h3>Limbo ({{$show->getApplicationsWithStatus(false)->count()}})</h3>
                            <ul class="inviteeList">
                                @foreach($show->getApplicationsWithStatus(false) as $application)
                                    <li>
                                        @if(auth()->user())
                                            <a href="/shows/{{$show->id}}/submission-applications/{{$application->id}}/view">@endif{{$application->name}}
                                                - {{$application->title}}@if(auth()->user())</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="rsvpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">RSVP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="" action="/shows/{{$show->id}}/invite/guest-request" method="POST">
                    <div class="modal-body row">
                        @csrf
                        <div class="col-md-6 my-1">
                            <input class="form-control" type="text" name="first_name" placeholder="First Name (required)">
                        </div>
                        <div class="col-md-6 my-1">
                            <input class="form-control" type="text" name="last_name" placeholder="Last Name (optional)">
                        </div>
                        <div class="col-md-12">
                            <div class="form-control my-2">
                                <div class="row ">
                                    <h4 class="col-12">Attending?</h4>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="response_status" id="yes" value="ATTENDING" checked>
                                    <label class="form-check-label" for="yes">
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="response_status" id="maybe" value="COWARD">
                                    <label class="form-check-label" for="maybe">
                                        Maybe
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="response_status" id="no" value="NO">
                                    <label class="form-check-label" for="no">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-control my-2">
                                <div class="row ">
                                    <h4 class="col-12">Bringing a plus one?</h4>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="plus_one_status" value="1" checked>
                                    <label class="form-check-label" for="true">
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="plus_one_status" value="0">
                                    <label class="form-check-label" for="false">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-end">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
