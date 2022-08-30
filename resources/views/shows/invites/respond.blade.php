@extends('shows.layout')
@push('meta')
    <meta property="og:title" content="{{$show->name}} - {{$invite->first_name}} Invitation" />
@endpush
@section('shows-content')
    <form action="/invite/respond/{{$invite->id}}" method="POST" class="card my-3">
        @csrf
        <div class="card-body">
            <h3 class="card-title">{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}} - RSVP <a href="/shows/{{$invite->show->id}}/view">
                        <button type="button" class="btn btn-info"><i class="fa-solid fa-worm"></i>
                            View Show</button>
                    </a></h3>
            <div class="form-control my-2">
                <div class="row ">
                    <h4 class="col-12">@if($show->at_capacity_attendants) Add to Attending Waitlist? @else Attending? @endif</h4>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="response_status" id="yes" value="ATTENDING" @if($invite->response_status == 'ATTENDING' || str_contains($invite->response_status, 'PENDING'))checked @endif>
                  <label class="form-check-label" for="yes">
                    Yes
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="response_status" id="no" value="NO" @if($invite->response_status == 'NO')checked @endif>
                  <label class="form-check-label" for="no">
                    No
                  </label>
                </div>
                @if(!$show->at_capacity_attendants)
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="response_status" id="maybe" value="COWARD" @if($invite->response_status == 'COWARD')checked @endif>
                      <label class="form-check-label" for="maybe">
                        Maybe
                      </label>
                    </div>
                @endif
            </div>
            <div class="talent-box form-control my-2">
                <div class="row ">
                    <h4 class="col-12">@if($show->at_capacity_talents) Add to Talent Waitlist? @else Talent? @endif</h4>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="talent" id="yes" value="1" @if($invite->talent)checked @endif>
                  <label class="form-check-label" for="yes">
                    Ye
                  </label>
                </div>
                <div class="talent-write-in-box">
                    <div class="form-label">If you already know, what is your talent? (optional)</div>
                    <input class="form-text" type="text" name="talent_write_in" placeholder="" value="{{$invite->talent_write_in}}">
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="talent" id="no" value="0" @if(!$invite->talent)checked @endif>
                  <label class="form-check-label" for="no">
                    Nay
                  </label>
                </div>
            </div>
            @if($invite->has_plus_one_option && !$show->at_capacity_attendants)
                <div class="plus-one-box form-control my-2">
                    <div class="row ">
                        <h4 class="col-12">Plus One?</h4>
                        <span><i>This does not include the option for the plus one to do a talent.</i></span>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="plus_one_status" id="yes" value="1" checked>
                      <label class="form-check-label" for="yes">
                          Si si
                      </label>
                    </div>
                    <div class="plus-one-name-box">
                        <div class="form-label">Who is this motherfucker?</div>
                        <input class="form-text" type="text" name="plus_one_name" placeholder="" value="{{$invite->plus_one_name}}">
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="plus_one_status" id="no" value="0">
                      <label class="form-check-label" for="no">
                        Non
                      </label>
                    </div>
                </div>
            @endif

            <div class="notifications-box form-control my-2">
                <div class="row">
                    <div class="col-md-6 my-1">
                        <label for="can_notify">Would you like to receive notifications about this event? (Current notification system only includes: Cancellations, Reschedulings, and Waitlist openings)</label>
                        <input class="" type="checkbox" name="can_notify" value="1" checked>
                    </div>
                </div>
                <div class="contact-box">
                    <p>Please enter your phone number or email to receive notifications:</p>
                    <div class="form-label">Phone: </div>
                    <input class="form-text" type="text" name="phone" placeholder="" value="{{$invite->phone}}">
                    <div class="form-label">Email: </div>
                    <input class="form-text" type="text" name="email" placeholder="" value="{{$invite->email}}">
                </div>
            </div>
        </div>
        <button class="btn btn-primary mx-3 mb-3" type="submit">Submit</button>
    </form>
@endsection
@push('scripts')
<script>
    @if($invite->response_status == 'PENDING - SENT')
        $(document).ready(function(){
            $.post( "/invites/{{$invite->id}}/mark-as-opened", {"_token": "{{ csrf_token() }}"});
        });
    @endif
    $('[name=response_status]').change(function(){
        if($(this).val() !== 'NO') {
            $('[name=talent]').removeAttr('disabled');
            $('.talent-box').show();
            $('.notifications-box').show();
            if($(this).val() == 'ATTENDING') {
                $('[name=plus-one-name]').removeAttr('disabled');
                $('.plus-one-box').show();
            } else {
                $('[name=plus-one-name]').attr('disabled', 'disabled');
                $('.plus-one-box').hide();
            }
        } else {
            $('[name=talent]').attr('disabled', 'disabled');
            $('.talent-box').hide();
            $('[name=plus-one-name]').attr('disabled', 'disabled');
            $('.plus-one-box').hide();
            $('.notifications-box').show();
        }
    });
    $('[name=talent]').change(function(){
        if($(this).val() !== '0')
        {
            $('[name=talent-write-in]').removeAttr('disabled');
            $('.talent-write-in-box').show();
        } else {
            $('[name=talent-write-in]').attr('disabled', 'disabled');
            $('.talent-write-in-box').hide();
        }
    });
    $('[name=plus_one_status]').change(function(){
        if($(this).val() !== '0')
        {
            $('[name=plus-one-name]').removeAttr('disabled');
            $('.plus-one-name-box').show();
        } else {
            $('[name=plus-one-name]').attr('disabled', 'disabled');
            $('.plus-one-name-box').hide();
        }
    });
    $('[name=can_notify]').click(function(){
        if ($(this).is(':checked')) {
            $(".contact-box").show();
        } else {
            $(".contact-box").hide();
        }
    });
</script>
@endpush
