@extends('shows.layout')
@section('shows-content')
    @if($show->date > Carbon\Carbon::today())
        <div class="card mt-3">
            <div class="card-title"></div>
            <div class="card-body">
                    <button class="btn btn-info copy-link form-control" data-link="{{route('invites.guest-request', ['show' => $show])}}">Guest Request Invite Link</button>
            </div>
        </div>
    @endif
    <div class="card my-3">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <h3>Attending ({{count($show->attending_invites)}})</h3>
                    <ul>
                        @foreach($show->attending_invites as $invite)
                            <li><i class="fa-regular fa-circle-check"></i> {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-3">
                    <h3>Maybe ({{count($show->maybe_invites)}})</h3>
                    <ul>
                        @foreach($show->maybe_invites as $invite)
                            <li><i class="fa-regular fa-circle-question"></i> {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-3">
                    <h3>No ({{count($show->no_invites)}})</h3>
                    <ul>
                        @foreach($show->no_invites as $invite)
                            <li><i class="fa-regular fa-circle-xmark"></i> {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-3">
                    <h3>Pending ({{count($show->pending_invites)}})</h3>
                    <ul>
                        @foreach($show->pending_invites as $invite)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
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
