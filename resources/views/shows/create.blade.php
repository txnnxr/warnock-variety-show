@extends('layouts.app')
@section('content')
<h2>@if($show) Edit {{$show->name}} @else Create A New Show @endif</h2>
<form class="form-check"
      @if($show)
          action="/shows/{{$show->id}}"
      @else
        action="/shows"
      @endif
      method="POST">
    @csrf
    @if($show)
        @method('PUT')
    @endif
    <label class="form-label col-3" for="name">Name:</label>
        <input class="form-control col-9" type="text" name="name" id="name" value="@if($show){{$show->name}}@endif"
               placeholder="Name">
    <label class="form-label col-3" for="description">Description:</label>
    <textarea type="text" name="description" class="form-control col-9" rows="6">@if($show){{htmlspecialchars_decode($show->description)}}@endif</textarea>
    <label class="form-label col-3" for="date">Address:</label>
        <input class="form-control col-9" type="text" name="address" id="address" value="@if($show){{$show->address}}@endif">
    <label class="form-label col-3" for="date">Date:</label>
        <input class="form-control col-9" type="datetime-local" name="date" id="date" value="@if($show){{$show->date->format('Y-m-d\TH:i')}}@endif" >
    <label class="form-label col-3" for="maxAttendants">Max Attendants:</label>
        <input class="form-control col-9" type="number" name="max_attendants" id="maxAttendants" value=@if($show){{$show->max_attendants}}@endif >
    <button class="form-control btn btn-primary" type="submit">@if($show) Edit @else Create @endif Show</button>
</form>
@endsection
