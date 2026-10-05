@extends('shows.layout')
@section('shows-content')
    <div class="card">
        <form action="/shows/{{$show->id}}/invite/guest-request" method="POST" class="card-body">
            @csrf
            <h2 class="section-heading">RSVP</h2>
            @include('shows.invites._guest-request-fields')
            <div class="d-grid d-sm-flex justify-content-sm-end mt-4">
                <button type="submit" class="btn btn-primary">Send RSVP</button>
            </div>
        </form>
    </div>
@endsection
