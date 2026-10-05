@extends('shows.layout')
@section('shows-content')
    @if($show->canceled)
        <div class="alert alert-warning">This show has been canceled, so RSVPs are closed.</div>
    @else
    <div class="card">
        <form action="/shows/{{$show->id}}/invite/guest-request" method="POST" class="card-body">
            @csrf
            @isset($guestLinkKey)
                <input type="hidden" name="guest_link_key" value="{{ $guestLinkKey }}">
            @endisset
            <h2 class="section-heading">RSVP</h2>
            @include('shows.invites._guest-request-fields')
            <div class="d-grid d-sm-flex justify-content-sm-end mt-4">
                <button type="submit" class="btn btn-primary">Send RSVP</button>
            </div>
        </form>
    </div>
    @endif
@endsection
