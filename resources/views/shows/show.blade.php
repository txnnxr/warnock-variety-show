@extends('shows.layout')
@section('shows-content')
    @if($show->date > Carbon\Carbon::today())
        @if(auth()->user())
            <div class="card mt-3">
                <div class="card-title"></div>

                <div class="card-body">
                    <button class="btn btn-info copy-link form-control" data-link="{{route('invites.guest-request', ['show' => $show])}}">Guest Request Invite Link</button>
                </div>
            </div>
        @else
            <div class="card mt-3">
                <div class="card-body p-4">
                    <div class="col-md-12 my-1">
                        <button class="form-control btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#rsvpModal">RSVP</button>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <div class="card my-3">
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-sm-3">
                    <h3 class="headers">Attending ({{count($show->attending_invites)}})</h3>

                    <ul class="inviteeList">
                        @foreach($show->attending_invites as $invite)

                            <li><i class="fa-regular fa-circle-check"></i> @if($invite->talent) <i class="fa-solid fa-otter"></i>  @else <i class="fa-solid fa-bugs"></i> @endif {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-6 col-sm-3">
                    <h3>Maybe ({{count($show->maybe_invites)}})</h3>
                    <ul class="inviteeList">
                        @foreach($show->maybe_invites as $invite)
                            <li><i class="fa-regular fa-circle-question"></i> @if($invite->talent) <i class="fa-solid fa-otter"></i>  @else <i class="fa-solid fa-bugs"></i> @endif  {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-6 col-sm-3">
                    <h3>No ({{count($show->no_invites)}})</h3>
                    <ul class="inviteeList">
                        @foreach($show->no_invites as $invite)
                            <li><i class="fa-regular fa-circle-xmark"></i> {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-6 col-sm-3">
                    <h3>Pending ({{count($show->pending_invites)}})</h3>
                    <ul class="inviteeList">
                        @foreach($show->pending_invites as $invite)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

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
                            <input class="form-control" type="text" name="first_name" placeholder="First Name (required)">
                        </div>
                        <div class="col-md-6 my-1">
                            <input class="form-control" type="text" name="last_name" placeholder="Last Name (optional)">
                        </div>
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
                            <div class="talent-box form-control my-2">
                            <div class="row ">
                                <h4 class="col-12">Talent?</h4>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="talent" id="yes" value="1" checked>
                                <label class="form-check-label" for="yes">
                                    Ye
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="talent" id="no" value="0">
                                <label class="form-check-label" for="no">
                                    Nay
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
@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).ready(function () {
                $('.copy-link').click(function () {
                    navigator.clipboard.writeText($(this).attr('data-link'));
                    $(this).text('Copied!');
                });
                $('#invitesTable').DataTable();
            });
            $('[name=response_status]').change(function(){
                if($(this).val() !== 'NO')
                {
                    $('[name=talent]').removeAttr('disabled');
                    $('.talent-box').show();
                }
                else
                {
                    $('[name=talent]').attr('disabled', 'disabled');
                    $('.talent-box').hide();
                }
            });
        });
    </script>
@endpush
