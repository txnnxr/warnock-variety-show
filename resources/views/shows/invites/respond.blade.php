@extends('shows.layout')
@push('meta')
    <meta property="og:title" content="{{$show->name}} - {{$invite->first_name}} Invitation" />
@endpush
@section('shows-content')

    <form action="/shows/{{$show->id}}/invite/respond/{{$invite->key}}" method="POST" class="card my-3">
        @csrf
        <div class="card-body">
            <h3 class="card-title">{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}} - RSVP</h3>
            <div class="form-control my-2">
                <div class="row ">
                    <h4 class="col-12">Attending?</h4>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="response_status" id="yes" value="ATTENDING" @checked(! in_array($invite->response_status, ['NO', 'COWARD']))>
                  <label class="form-check-label" for="yes">
                    Yes
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="response_status" id="no" value="NO" @checked($invite->response_status == 'NO')>
                  <label class="form-check-label" for="no">
                    No
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="response_status" id="maybe" value="COWARD" @checked($invite->response_status == 'COWARD')>
                  <label class="form-check-label" for="maybe">
                    Maybe
                  </label>
                </div>
            </div>
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
            @if($invite->has_plus_one_option)
                <div class="plus-one-box form-control my-2">
                    <div class="row ">
                        <h4 class="col-12">Bringing a plus one?</h4>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="plus_one_status" id="plus-one-yes" value="1" @checked($invite->plus_one_status)>
                        <label class="form-check-label" for="plus-one-yes">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="plus_one_status" id="plus-one-no" value="0" @checked(! $invite->plus_one_status)>
                        <label class="form-check-label" for="plus-one-no">
                            No
                        </label>
                    </div>
                </div>
            @endif
        </div>
        <button class="btn btn-primary mx-3 mb-3" type="submit">Submit</button>
    </form>
@endsection
@push('scripts')
<script>
    @if($invite->response_status == 'PENDING - SENT')
        $(document).ready(function(){
            $.post( "{{ route('invites.mark-as-opened', $invite) }}", {"_token": "{{ csrf_token() }}"});
        });
    @endif
    $('[name=response_status]').change(function(){
        if($(this).val() !== 'NO')
        {
            $('[name=talent], [name=plus_one_status]').removeAttr('disabled');
            $('.talent-box, .plus-one-box').show();
        }
        else
        {
            $('[name=talent], [name=plus_one_status]').attr('disabled', 'disabled');
            $('.talent-box, .plus-one-box').hide();
        }
    });
</script>
@endpush
