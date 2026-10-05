@extends('shows.layout')
@section('shows-content')
    @if($show->date > Carbon\Carbon::today() && ! $show->canceled)
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6 d-grid">
                        <button class="btn btn-primary btn-lg" type="button" data-bs-toggle="modal" data-bs-target="#rsvpModal">RSVP</button>
                    </div>
                    <div class="col-12 col-md-6 d-grid">
                        <a href="/shows/{{$show->id}}/submission-applications/create" class="btn btn-success btn-lg">Exhibition Application</a>
                    </div>
                </div>
                <p class="text-center mt-3 mb-0 fst-italic">The address is shared with confirmed guests after they RSVP.</p>
            </div>
        </div>
    @endif

    @if(count($show->lineup))
        <div class="card">
            <div class="card-body">
                <h2 class="section-heading">The Lineup</h2>
                <ol class="lineup">
                    @foreach($show->lineup as $act)
                        <li>
                            <span class="act">
                                @can('admin')
                                    <a href="/shows/{{$show->id}}/submission-applications/{{$act->submission_application_id}}/view">{{$act->exhibition_description}}</a>
                                @else
                                    {{$act->exhibition_description}}
                                @endcan
                            </span>
                            <span class="leader" aria-hidden="true"></span>
                            <span class="performer">{{$act->person->name}}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h2 class="section-heading">Who's Coming</h2>
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-lg-4">
                    <h3 class="h5">Attending
                        <span class="text-muted fs-6">({{ $show->seatsTaken() }}@if($show->max_attendants > 0) of {{$show->max_attendants}}@endif)</span>
                    </h3>
                    @if(count($show->attending_invites))
                        <ul class="guest-list">
                            @foreach($show->attending_invites as $invite)
                                <li>
                                    @if($invite->talent)
                                        <i class="fa-solid fa-otter" title="Has a talent"></i>
                                    @else
                                        <i class="fa-solid fa-bugs" title="Here to watch"></i>
                                    @endif
                                    <span>{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}} @if($invite->plus_one_status) <span class="text-muted">(+1)</span> @endif</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted fst-italic">No one yet. Be the first!</p>
                    @endif
                </div>
                @if(count($show->waitlist_invites))
                    <div class="col-12 col-sm-6 col-lg-4">
                        <h3 class="h5">Waitlist <span class="text-muted fs-6">({{count($show->waitlist_invites)}})</span></h3>
                        <ol class="guest-list">
                            @foreach($show->waitlist_invites as $invite)
                                <li>{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}} @if($invite->plus_one_status) <span class="text-muted">(+1)</span> @endif</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
                @if(count($show->maybe_invites))
                    <div class="col-12 col-sm-6 col-lg-4">
                        <h3 class="h5">Maybe <span class="text-muted fs-6">({{count($show->maybe_invites)}})</span></h3>
                        <ul class="guest-list">
                            @foreach($show->maybe_invites as $invite)
                                <li>
                                    <i class="fa-regular fa-circle-question"></i>
                                    <span>{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @can('admin')
                    @if($show->getApplicationsWithStatus(false)->count())
                        <div class="col-12 col-sm-6 col-lg-4">
                            <h3 class="h5">Limbo <span class="text-muted fs-6">({{$show->getApplicationsWithStatus(false)->count()}})</span></h3>
                            <ul class="guest-list">
                                @foreach($show->getApplicationsWithStatus(false) as $application)
                                    <li>
                                        <i class="fa-solid fa-scroll"></i>
                                        <a href="/shows/{{$show->id}}/submission-applications/{{$application->id}}/view">{{$application->name}} - {{$application->title}}</a>
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
        <div class="card">
            <div class="card-body">
                <h2 class="section-heading">Photos</h2>
                <div class="photo-grid">
                    @foreach($show->photos as $photo)
                        <figure>
                            <a href="{{ $photo->url }}" target="_blank"><img src="{{ $photo->url }}" alt="{{ $photo->caption ?? $show->name }}" loading="lazy"></a>
                            @if($photo->caption)<figcaption>{{ $photo->caption }}</figcaption>@endif
                            @can('admin')
                                <form method="POST" action="{{ route('photos.destroy', $photo) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger mt-1">Delete</button>
                                </form>
                            @endcan
                        </figure>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @can('admin')
        @if($show->canceled)
            <div class="card">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <p class="mb-0">This show is canceled. It's hidden from the home page and closed to RSVPs and applications.</p>
                    <form method="POST" action="{{ route('shows.restore', $show) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">Restore Show</button>
                    </form>
                </div>
            </div>
        @elseif(! $show->isPast())
            @php($interested = $show->interestedGuests()->count())
            <div class="card">
                <div class="card-body">
                    <h2 class="section-heading">Cancel This Show</h2>
                    <form method="POST" action="{{ route('shows.cancel', $show) }}" x-data="{ sure: false }">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="cancel-note">Note to guests <span class="text-muted">(optional)</span></label>
                            <textarea class="form-control" id="cancel-note" name="note" rows="2" maxlength="1000" placeholder="e.g. The host has the flu. We'll reschedule soon!"></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="cancel-notify" name="notify" value="1" checked>
                            <label class="form-check-label" for="cancel-notify">Email the {{ $interested }} {{ Str::plural('guest', $interested) }} who haven't said no</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="cancel-confirm" x-model="sure" required>
                            <label class="form-check-label" for="cancel-confirm">Yes, cancel {{ $show->name }}</label>
                        </div>
                        <button type="submit" class="btn btn-danger" x-bind:disabled="! sure">Cancel Show</button>
                    </form>
                </div>
            </div>
        @endif
        @if($show->isPast() && ! $show->canceled)
            @php($recipients = $show->recapRecipients()->count())
            <div class="card">
                <div class="card-body">
                    <h2 class="section-heading">Send the Recap</h2>
                    <p>Thank the {{ $recipients }} confirmed {{ Str::plural('guest', $recipients) }} with an email address. The recap credits the lineup, shows up to three photos, and asks what they'd like more of. Upload photos first if you have them.</p>
                    @error('recap')<div class="alert alert-danger">{{ $message }}</div>@enderror
                    <form method="POST" action="{{ route('shows.send-recap', $show) }}" class="d-flex flex-wrap align-items-center gap-3">
                        @csrf
                        <button type="submit" class="btn btn-primary" @disabled($recipients === 0)>
                            <i class="fa-solid fa-paper-plane"></i> {{ $show->recap_sent_at ? 'Send Again' : 'Email the Recap' }}
                        </button>
                        @if($show->recap_sent_at)
                            <span class="text-muted">Last sent {{ $show->recap_sent_at->format('M j \a\t g:ia') }}</span>
                        @endif
                    </form>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <h2 class="section-heading">Upload Photos</h2>
                <form method="POST" action="{{ route('photos.store', $show) }}" enctype="multipart/form-data" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="photos">Photos</label>
                        <input class="form-control" type="file" id="photos" name="photos[]" accept="image/*" multiple required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="caption">Caption</label>
                        <input class="form-control" type="text" id="caption" name="caption" placeholder="Optional">
                    </div>
                    <div class="col-12 col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                    @error('photos.*')<div class="text-danger">{{ $message }}</div>@enderror
                </form>
            </div>
        </div>
    @endcan

    <div class="modal fade" id="rsvpModal" tabindex="-1" aria-labelledby="rsvpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form action="/shows/{{$show->id}}/invite/guest-request" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="modal-title h4" id="rsvpModalLabel">RSVP for {{ $show->name }}</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('shows.invites._guest-request-fields')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send RSVP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
