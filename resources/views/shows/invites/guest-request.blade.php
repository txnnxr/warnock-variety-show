@extends('shows.layout')
@section('shows-content')
            <div class="card">
                <form class="" action="/shows/{{$show->id}}/invite/guest-request" method="POST">
                <div class="card-header">
                    <h5 class="modal-title" id="exampleModalLabel">RSVP</h5>
                </div>
                    <div class="card-body row">
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
                            <div class="form-control my-2">
                                <div class="row ">
                                    <h4 class="col-12">Bringing a plus one?</h4>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="plus_one_status" value="1" checked>
                                    <label class="form-check-label" for="true">
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="plus_one_status" value="false">
                                    <label class="form-check-label" for="false">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary text-end">Submit</button>
                    </div>
                </form>
            </div>
@endsection
