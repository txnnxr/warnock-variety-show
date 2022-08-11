@extends('shows.layout')
@section('shows-content')
    <div class="card mt-3">
        <div class="card-title"></div>
        <div class="card-body">
            <button class="btn btn-info copy-link form-control" data-link="{{route('invites.guest-request', ['show' => $show])}}">Guest Request Invite Link</button>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body p-4">
            <h5 class="card-title text-center">Generate Invite</h5>
            <form class="row" action="/shows/{{$show->id}}/invite" method="POST">
                @csrf
                <div class="col-md-6 my-1">
                    <input class="form-control" type="text" name="first_name" placeholder="First Name (required)">
                </div>
                <div class="col-md-6 my-1">
                    <input class="form-control" type="text" name="last_name" placeholder="Last Name (optional)">
                </div>
                <div class="col-md-6 my-1">
                    <input class="form-control" type="text" name="phone" placeholder="Phone (optional)">
                </div>
                <div class="col-md-6 my-1">
                    <input class="form-control" type="text" name="email" placeholder="Email (optional)">
                </div>
                <div class="col-md-6 my-1">
                    <label for="has_plus_one_option">Give invitation optional plus one?</label>
                    <input class="" type="checkbox" name="has_plus_one_option" value="1">
                </div>
                <div class="col-md-12 my-1">
                    <button class="form-control btn btn-primary" type="submit">Create Invite</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card my-3">
        <div class="card-title mt-3 px-3">
            <div class="row">
                <div class="col">Total Invites: {{count($show->invites)}}</div>
                <div class="col">Attending: {{count($show->attending_invites)}}</div>
                <div class="col">Maybe: {{count($show->maybe_invites)}}</div>
                <div class="col">No: {{count($show->no_invites)}} </div>
                <div class="col">Pending: {{count($show->pending_invites)}}</div>
                <div class="col">Created: {{count($show->created_invites)}} </div>
            </div>
        </div>
        <div class="card-body">
            <table class="dt-responsive no-wrap" id="invitesTable">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Response</th>
                        <th>Talent</th>
                        <th>Buttons</th>
                    </tr>
                  </thead>
                    <tbody>
                    @php
                        $index = 1;
                    @endphp
                        @foreach($show->invites as $invite)
                            <tr>
                                <td>{{$index++}}</td>
                                <td>{{$invite->first_name}} {{$invite->middle_name}}
                                    {{$invite->last_name}} @if($invite->has_plus_one_option) (+1) @endif</td>
        {{--                        <td>@if($invite->phone){{$invite->phone}}@else{{$invite->email}}@endif</td>--}}
                                <td>{{$invite->response_status}} @if($invite->guest_request) - REQUESTED @endif</td>
                                <td>@if($invite->talent) YES @else NO @endif</td>
                                <td>
                                    <a href="{{action([\App\Http\Controllers\InviteController::class, 'edit'], ['invite' => $invite])}}" class="btn btn-secondary col">Edit</a>
                                    @if($invite->response_status == 'CREATED')
                                        @if($invite->phone || $invite->email)
                                            <form class="d-inline-block" action="{{action([\App\Http\Controllers\InviteController::class, 'send'], ['invite' => $invite])}}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-warning col d-inline-block" href>Send</button>
                                            </form>
                                        @else
                                             <a class="btn btn-primary copy-link col" data-link="{{$invite->link}}">Link</a>
                                        @endif
                                    @endif
                                    @if($invite->guest_request)
                                        <form class="d-inline-block" action="{{route('invites.guest-request.approve', ['invite' => $invite])}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success col d-inline-block" href>Approve</button>
                                        </form>
                                    @endif
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
                $(this).text('Copied!');
            });
            $('#invitesTable').DataTable();
        });
    </script>
@endpush
