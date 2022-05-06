@extends('shows.layout')
@section('shows-content')
    <div class="card-body">
        <h5 class="card-title">Thank you {{$invite->first_name}} {{$invite->middle_name}}
            {{$invite->last_name}}! Your response has been saved!</h5>
        <div class="form-control my-2">
            <div class="row">
                <div class="col-12">Attending?</div>
            </div>
            <div class="row">
                <div class="col-12">{{$invite->response_status}}</div>
            </div>
        </div>
        @if($invite->response_status == "ATTENDING")
            <div class="talent-box form-control my-2">
                <div class="row ">
                    <div class="col-12">Talent?</div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @if($invite->talent)
                            Yes
                        @else
                            No
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
