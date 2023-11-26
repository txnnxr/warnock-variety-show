@extends('layouts.app')
@section('content')
<h2>@if(isset($submissionApplication)) Edit {{$submissionApplication->title}} @else Exhibit Application @endif</h2>
<form class="form-check"
      @if(isset($submissionApplication))
          action="/shows/{{$show->id}}/submission-applications/{{$submissionApplication->id}}"
      @else
        action="/shows/{{$show->id}}/submission-applications"
      @endif
      method="POST">
    @csrf
    @if(isset($submissionApplication))
        @method('PUT')
    @endif
    <label class="form-label col-3" for="name">Name:</label>
        <input class="form-control col-9" type="text" name="name" id="name" value="@if(isset($submissionApplication)){{$submissionApplication->name}}@endif"
               placeholder="First, Middle, Confirmation, Last">
    <label class="form-label col-3" for="name">Title:</label>
        <input class="form-control col-9" type="text" name="title" id="title" value="@if(isset($submissionApplication)){{$submissionApplication->title}}@endif"
               placeholder="Required" required>
    <label class="form-label col-3" for="name">Phone:</label>
        <input class="form-control col-9" type="text" name="phone" id="phone" value="@if(isset($submissionApplication)){{$submissionApplication->phone}}@endif"
               placeholder="Like loyalty... optional, but appreciated.">
        <label class="form-label col-3" for="name">Email:</label>
        <input class="form-control col-9" type="text" name="email" id="email" value="@if(isset($submissionApplication)){{$submissionApplication->email}}@endif"
               placeholder="Like loyalty... optional, but appreciated.">
    <label class="form-label col-3" for="description">Description:</label>
    <textarea type="text" name="description" class="form-control col-9" rows="6" placeholder="If not covered by the title, let us know a little bit about what you're planning. Please also use this space to let us know of any requirements you might have technical or otherwise that you'll need for your exhibition (Mics, amps, projector, etc.). If you're not bringing it, list it! So we can make sure we have everything prepared!">@if(isset($submissionApplication)){{htmlspecialchars_decode($submissionApplication->description)}}@endif</textarea>
    <button class="form-control btn btn-primary mt-2" type="submit">@if(isset($submissionApplication)) Edit @else Submit @endif Application</button>
</form>
@endsection
