@extends('shows.layout')
@section('shows-content')
    @if(session('status'))
        <div class="alert alert-success mt-3">{{ session('status') }}</div>
    @endif
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
                <p class="text-center mt-3 mb-0"><i>The address is shared with confirmed guests after they RSVP.</i></p>
            </div>
        </div>
    @endif
    <div class="card card-alt-2 my-3">
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-sm-3">
                    <h3 class="headers">Attending ({{count($show->attending_invites) + count($show->attending_invites_with_plus_one)}}@if($show->max_attendants > 0)/{{$show->max_attendants}}@endif)</h3>

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
                @if(count($show->waitlist_invites))
                    <div class="col-6 col-sm-3">
                        <h3>Waitlist ({{count($show->waitlist_invites)}})</h3>
                        <ol class="inviteeList">
                            @foreach($show->waitlist_invites as $invite)
                                <li>{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}} @if($invite->plus_one_status) (+1) @endif</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
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
                @if(count($show->lineup))
                    <div class="col-6 col-sm-3">
                        <h3 class="headers">Lineup ({{count($show->lineup)}})</h3>

                        <ol class="inviteeList">
                            @foreach($show->lineup as $act)
                                <li>
                                    @can('admin')
                                        <a href="/shows/{{$show->id}}/submission-applications/{{$act->submission_application_id}}/view">@endcan{{$act->person->name}} - {{$act->exhibition_description}}@can('admin')</a>
                                    @endcan
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
                @can('admin')
                    @if($show->getApplicationsWithStatus(false)->count())
                        <div class="col-6 col-sm-3">
                            <h3>Limbo ({{$show->getApplicationsWithStatus(false)->count()}})</h3>
                            <ul class="inviteeList">
                                @foreach($show->getApplicationsWithStatus(false) as $application)
                                    <li>
                                        <a href="/shows/{{$show->id}}/submission-applications/{{$application->id}}/view">{{$application->name}}
                                            - {{$application->title}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>
    @if(count($show->photos))
        <div class="card my-3">
            <div class="card-body">
                <h3 class="headers">Photos</h3>
                <div class="row g-2">
                    @foreach($show->photos as $photo)
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ $photo->url }}" target="_blank"><img src="{{ $photo->url }}" class="img-fluid rounded" alt="{{ $photo->caption ?? $show->name }}" loading="lazy"></a>
                            @if($photo->caption)<div class="small">{{ $photo->caption }}</div>@endif
                            @can('admin')
                                <form method="POST" action="{{ route('photos.destroy', $photo) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger mt-1">Delete</button>
                                </form>
                            @endcan
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    @can('admin')
        <div class="card my-3">
            <div class="card-body">
                <h3 class="headers">Upload Photos</h3>
                <form method="POST" action="{{ route('photos.store', $show) }}" enctype="multipart/form-data" class="row g-2">
                    @csrf
                    <div class="col-md-6">
                        <input class="form-control" type="file" name="photos[]" accept="image/*" multiple required>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control" type="text" name="caption" placeholder="Caption (optional)">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary form-control">Upload</button>
                    </div>
                    @error('photos.*')<div class="text-danger">{{ $message }}</div>@enderror
                </form>
            </div>
        </div>
    @endcan
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
                            <input class="form-control" type="text" name="first_name" placeholder="First Name (required)" required>
                        </div>
                        <div class="col-md-6 my-1">
                            <input class="form-control" type="text" name="last_name" placeholder="Last Name (optional)">
                        </div>
                        <div class="col-md-6 my-1">
                            <input class="form-control" type="email" name="email" placeholder="Email (optional)">
                        </div>
                        <div class="col-md-6 my-1">
                            <input class="form-control" type="text" name="phone" placeholder="Phone (optional)">
                        </div>
                        <div class="col-12 small text-muted">Leave an email to hear when you're approved, or if you come off the waitlist.</div>
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
                                    <input class="form-check-input" type="radio" name="plus_one_status" id="plus-one-yes" value="1" checked>
                                    <label class="form-check-label" for="plus-one-yes">
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="plus_one_status" id="plus-one-no" value="0">
                                    <label class="form-check-label" for="plus-one-no">
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
