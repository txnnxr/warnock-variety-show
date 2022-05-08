@extends('shows.layout')
@section('shows-content')
    <div class="card mt-2">
        <div class="card-body p-4">
            <h5 class="card-title text-center">Request Invite</h5>
            <form class="row" action="/shows/{{$show->id}}/invite/guest-request" method="POST">
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
                <div class="col-md-12 my-1">
                    <button class="form-control btn btn-primary" type="submit">Request Invite</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('[name=response_status]').change(function(){
        if($(this).val() === 'ATTENDING')
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
    </script>
@endpush
