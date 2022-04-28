@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <h2 class="text-center">{{$show->name}}</h2>
            <h3 class="text-center">{{$show->address}}</h3>
            <h3 class="text-center">{{$show->date}}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12">{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}</div>
    </div>
    <div class="row">
        <div class="col-12">RSVP</div>
    </div>
    <form action="/shows/{{$show->id}}/invite/respond/{{$invite->key}}" method="POST" class="container form-control">
        <div class="row ">
            <div class="col-12">Attending?</div>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="attendance_response" id="yes" value="yes" checked>
          <label class="form-check-label" for="yes">
            Yes
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="attendance_response" id="no" value="no">
          <label class="form-check-label" for="no">
            No
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="attendance_response" id="maybe" value="maybe">
          <label class="form-check-label" for="maybe">
            Coward (Maybe)
          </label>
        </div>
        <div class="row talent-box">
            <div class="row ">
                <div class="col-12">Talent?</div>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="talent_response" id="yes" value="yes" checked>
              <label class="form-check-label" for="yes">
                Ye
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="talent_response" id="no" value="no">
              <label class="form-check-label" for="no">
                Nay
              </label>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
<script>
    $('[name=attendance_response]').change(function(){
      if($(this).val() === 'yes')
      {
          $('.talent-box').show();
      }
      else
      {
          $('.talent-box').hide();
      }
    });
</script>
@endpush
