@extends('shows.layout')
@section('shows-content')
    <div class="card mt-2">
        <div class="card-body p-4">
            <h5 class="card-title text-center">Generate Invite</h5>
            <form class="row" action="/shows/{{$show->id}}/invite" method="POST">
                @csrf
                <div class="col-md-6 my-1">
                    <input class="form-control" type="text" name="first_name" placeholder="First Name">
                </div>
                <div class="col-md-6 my-1">
                    <input class="form-control" type="text" name="last_name" placeholder="Last Name">
                </div>
                <div class="col-md-6 my-1">
                    <input class="form-control" type="text" name="phone" placeholder="Phone">
                </div>
                <div class="col-md-6 my-1">
                    <input class="form-control" type="text" name="email" placeholder="Email">
                </div>
                <div class="col-md-12 my-1">
                    <button class="form-control btn btn-primary" type="submit">Create Invite</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-body p-4">
            <h5 class="card-title text-center">Sent Invites</h5>
            <div class="row">
                <div class="col-3">Name</div>
                <div class="col-3">Contact</div>
                <div class="col-2">Response</div>
                <div class="col-2">Talent</div>
                <div class="col-2"></div>
            </div>
            @foreach($show->invites as $invite)
                <div class="row my-2">
                    <div class="col-3">{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</div>
                    <div class="col-3">@if($invite->phone){{$invite->phone}}@else{{$invite->email}}@endif</div>
                    <div class="col-2">{{$invite->response_status}}</div>
                    <div class="col-1">@if($invite->talent) YES @else NO @endif</div>
                    <div class="col-3">
                        <a class="btn btn-secondary" width="50%">Edit</a>
                        <a class="btn btn-primary copy-link" width="50%" data-link="{{$invite->link}}">Link</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.copy-link').click(function(){
            navigator.clipboard.writeText($(this).attr('data-link'));
            $(this).text('Copied!');
        });
    </script>
@endpush
