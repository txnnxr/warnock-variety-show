@extends('shows.layout')
@section('shows-content')
    <div class="card">
        <div class="card-body">
            <h2 class="section-heading">At a Glance</h2>
            <div class="row row-cols-3 row-cols-md-6 g-3">
                <div class="col stat"><div class="stat-value">{{ $show->seatsTaken() }}@if($show->max_attendants > 0)<span class="fs-6 text-muted">/{{ $show->max_attendants }}</span>@endif</div><div class="stat-label">Seats</div></div>
                <div class="col stat"><div class="stat-value">{{ count($show->attending_invites) }}</div><div class="stat-label">Attending</div></div>
                <div class="col stat"><div class="stat-value">{{ count($show->waitlist_invites) }}</div><div class="stat-label">Waitlist</div></div>
                <div class="col stat"><div class="stat-value">{{ count($show->maybe_invites) }}</div><div class="stat-label">Maybe</div></div>
                <div class="col stat"><div class="stat-value">{{ count($show->pending_invites) + count($show->created_invites) }}</div><div class="stat-label">No Reply</div></div>
                <div class="col stat"><div class="stat-value">{{ count($show->pending_requests) }}</div><div class="stat-label">Requests</div></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card h-100">
                <form class="card-body" action="/shows/{{$show->id}}/invite" method="POST">
                    @csrf
                    <h2 class="section-heading">New Invite</h2>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label" for="invite-first-name">First name</label>
                            <input class="form-control" type="text" id="invite-first-name" name="first_name" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" for="invite-last-name">Last name <span class="text-muted">(optional)</span></label>
                            <input class="form-control" type="text" id="invite-last-name" name="last_name">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" for="invite-phone">Phone <span class="text-muted">(optional)</span></label>
                            <input class="form-control" type="tel" id="invite-phone" name="phone">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" for="invite-email">Email <span class="text-muted">(optional)</span></label>
                            <input class="form-control" type="email" id="invite-email" name="email">
                        </div>
                    </div>
                    <div class="d-grid d-sm-flex justify-content-sm-end mt-3">
                        <button class="btn btn-primary" type="submit">Create Invite</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="card h-100">
                <div class="card-body d-flex flex-column gap-3">
                    <h2 class="section-heading">Spread the Word</h2>
                    <form action="{{ route('invites.send-all', $show) }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Email All Unsent Invites</button>
                    </form>
                    <button class="btn btn-outline-secondary copy-link" data-link="{{route('invites.guest-request', ['show' => $show])}}"><i class="fa-solid fa-link"></i> Copy Guest Request Link</button>
                    <p class="small text-muted mb-0">Anyone with the guest request link can ask to come. Requests hold no seat until you approve them.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="section-heading">Invites ({{ count($show->invites) }})</h2>
            <table class="table dt-responsive w-100" id="invitesTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Response</th>
                        <th>Talent</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($show->invites as $invite)
                        <tr>
                            <td>
                                @if($invite->person_id)
                                    <a href="{{ route('people.show', $invite->person_id) }}">{{ $invite->full_name }}</a>
                                @else
                                    {{ $invite->full_name }}
                                @endif
                                @if($invite->plus_one_status) <span class="text-muted">(+1)</span> @endif
                            </td>
                            <td><x-rsvp-status :invite="$invite" /></td>
                            <td>{{ $invite->talent ? 'Yes' : 'No' }}</td>
                            <td class="text-break">{{ $invite->email ?: $invite->phone }}</td>
                            <td>
                                <div class="btn-group-actions">
                                    <button type="button" class="btn btn-sm btn-outline-secondary copy-link" data-link="{{$invite->link}}">Copy Link</button>
                                    @if($invite->email)
                                        <form action="{{ route('invites.send', $invite) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Email</button>
                                        </form>
                                    @endif
                                    @if($invite->response_status == 'CREATED')
                                        <form action="/invites/{{$invite->id}}/mark-as-sent" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Mark Sent</button>
                                        </form>
                                    @endif
                                    @if($invite->guest_request)
                                        <form action="{{route('invites.guest-request.approve', ['invite' => $invite])}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('.copy-link').click(function () {
                navigator.clipboard.writeText($(this).attr('data-link'));
                var button = $(this), label = button.html();
                button.text('Copied!');
                setTimeout(function () { button.html(label); }, 1500);
            });
            $('#invitesTable').DataTable({ responsive: true, pageLength: 25 });
        });
    </script>
@endpush
