@extends('layouts.app')
@section('content')
<h2>@if(isset($submissionApplication)) Edit {{$submissionApplication->title}} @else Exhibit Application @endif</h2>
<div class="card">
    <div class="card-body">
        <h3 class="card-title">Exhibitioner Sign Up Process</h3>
        <ul>
            <li>Read the Exhibitioner Rules.</li>
            <li>Fill out the Exhibit Details form.</li>
            <li>If your application is approved you will receive a confirmation message at one of the contact methods you listed on the Details form. If you don't receive a message from me then you're not in the show. At the latest you will receive confirmation about your spot 1 week before the show. (Unless you apply within a week of the show, obviously, loser.)</li>
        </ul>
        <p><i>Generally speaking, exhibit spots will be filled on a first come first serve basis unless I feel the lineup is lacking sufficient variety.</i></p>
    </div>
</div>
<br>
<div class="card">
    <div class="card-body">
        <h3 class="card-title">Exhibitioner Rules</h3>
        <ul>
            <li>You must be in the room before the start time of the show (8pm). This allows us to solidify the order of the lineup beforehand and start the show on time.</li>
            <li>Exhibitions are expected to be kept to 15 minutes max (Setup time is NOT included in this time). If you hit the time limit you will be given a two minute warning to wrap it up.</li>
            <li>If for any reason you can't abide by the above rules you can reach out to me directly at least 48 hours before the start time of the show to try to work something out.</li>
        </ul>
        <strong>Failure to follow the above rules will result in you being unable to exhibit at future shows and may even put your attendance as an audience member at future shows in jeopardy if it causes too big of a headache to me personally.</strong>
    </div>
</div>
<br>
<div class="card">
    <div class="card-body">
        <h3 class="card-title">Exhibit Details</h3>
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
        <label class="form-label col-3" for="name">Exhibition Title:</label>
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
    </div>
</div>

@endsection
