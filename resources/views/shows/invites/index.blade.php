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
    <div class="card my-3">
        <div class="card-title">Total Invites: {{count($show->invites)}} Attending: {{count($show->attending_invites)}}
         Pending: {{count($show->pending_invites)}} No: {{count($show->no_invites)}}</div>
        <div class="card-body">
            <table class="table tabled-bordered dt-responsive no-wrap" id="invitesTable">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
        {{--                <th scope="col-1">Contact</th>--}}
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
                                    {{$invite->last_name}}</td>
        {{--                        <td>@if($invite->phone){{$invite->phone}}@else{{$invite->email}}@endif</td>--}}
                                <td>{{$invite->response_status}}</td>
                                <td>@if($invite->talent) YES @else NO @endif</td>
                                <td>
                                    <a class="btn btn-secondary" width="50%">Edit</a>
                                    <a class="btn btn-primary copy-link" width="50%" data-link="{{$invite->link}}">Link</a>
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
