@extends('shows.layout')
@section('shows-content')
    @livewire('rsvp', compact('show'))
    <div class="card my-3">
{{--        <div class="card-title mt-3 px-3">--}}
{{--            <div class="row">--}}
{{--                <div class="col">Total Invites: {{count($show->invites)}}</div>--}}
{{--                <div class="col">Attending: {{count($show->attending_invites)}}</div>--}}
{{--                <div class="col">Maybe: {{count($show->maybe_invites)}}</div>--}}
{{--                <div class="col">No: {{count($show->no_invites)}} </div>--}}
{{--                <div class="col">Pending: {{count($show->pending_invites)}}</div>--}}
{{--                <div class="col">Created: {{count($show->created_invites)}} </div>--}}
{{--            </div>--}}
{{--        </div>--}}
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
                                    <a class="btn btn-primary copy-link col" data-link="{{$invite->link}}">Link</a>
                                    @if($invite->response_status == 'CREATED')
                                        <form class="d-inline-block" action="/invites/{{$invite->id}}/mark-as-sent" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning col d-inline-block" href>Sent</button>
                                        </form>
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
