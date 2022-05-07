@extends('shows.layout')
@section('shows-content')
    <div class="card my-3">
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
                <div class="row">
                    <div class="col">
                        <form method="post" action="/invites/{{$invite->id}}/generate-ics">
                            @csrf
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-kiwi-bird"></i> Add
                                to Calendar
                            </button>
                        </form>
                    </div>
                    <div class="col">
                        <a href="/invites/{{$invite->id}}/edit">
                            <button type="submit" class="btn btn-warning"><i class="fa-solid fa-hippo"></i>
                                Update Response</button>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
