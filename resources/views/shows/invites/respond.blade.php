@extends('shows.layout')
@section('shows-content')

    <form action="/shows/{{$show->id}}/invite/respond/{{$invite->key}}" method="POST" class="card">
        @csrf
        <div class="card-body">
            <h5 class="card-title">{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}} - RSVP</h5>
            <div class="form-control my-2">
                <div class="row ">
                    <div class="col-12">Attending?</div>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="response_status" id="yes" value="ATTENDING" checked>
                  <label class="form-check-label" for="yes">
                    Yes
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="response_status" id="no" value="NO">
                  <label class="form-check-label" for="no">
                    No
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="response_status" id="maybe" value="COWARD">
                  <label class="form-check-label" for="maybe">
                    Coward (Maybe)
                  </label>
                </div>
            </div>
            <div class="talent-box form-control my-2">
                <div class="row ">
                    <div class="col-12">Talent?</div>
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
        <button class="btn btn-primary mx-3 mb-3" type="submit">Submit</button>
    </form>
@endsection
@push('scripts')
<script>
    $('[name=response_status]').change(function(){
      if($(this).val() === 'ATTENDING')
      {
          $('[name=talent]').attr('disabled', 'false');
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
