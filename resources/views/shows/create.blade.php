@extends('layouts.app')
@section('content')
<h2>Create A New Show</h2>
<form class="form-check" action="/shows" method="POST">
    @csrf
    <label class="form-label col-3" for="name">Name:</label>
        <input class="form-control col-9" type="text" name="name" id="name">
    <label class="form-label col-3" for="description">Description:</label>
    <input type="text" name="description" class="form-control col-9">
{{--        <textarea class="form-control col-9" maxlength="5000" name="description" id="description" ></textarea>--}}
    <label class="form-label col-3" for="date">Address:</label>
        <input class="form-control col-9" type="text" name="address" id="address">
    <label class="form-label col-3" for="date">Date:</label>
        <input class="form-control col-9" type="datetime-local" name="date" id="date">
    <label class="form-label col-3" for="maxAttendants">Max Attendants:</label>
        <input class="form-control col-9" type="number" name="max_attendants" id="maxAttendants">
    <button class="form-control btn btn-primary" type="submit">Create Show</button>
</form>
@endsection
