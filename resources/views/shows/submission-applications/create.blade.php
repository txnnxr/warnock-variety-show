@extends('layouts.app')
@section('title', 'Exhibit Application')
@section('content')
<div class="card playbill">
    <div class="card-body">
        <div class="ornament small-caps mb-2">{{ $show->name }} · {{ $show->date->format('F j, Y') }}</div>
        <h1 class="card-heading">Exhibit Application</h1>
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <h2 class="section-heading">Sign Up Process</h2>
                <ul>
                    <li>Read the Exhibitioner Rules.</li>
                    <li>Fill out the Exhibit Details form.</li>
                    <li>If your application is approved you will receive a confirmation message at one of the contact methods you listed on the Details form. If you don't receive a message from me then you're not in the show. At the latest you will receive confirmation about your spot 1 week before the show. (Unless you apply within a week of the show, obviously, loser.)</li>
                </ul>
                <p><i>Generally speaking, exhibit spots will be filled on a first come first serve basis unless I feel the lineup is lacking sufficient variety.</i></p>
            </div>
            <div class="col-12 col-lg-6">
                <h2 class="section-heading">Exhibitioner Rules</h2>
                <ul>
                    <li>You must be in the room before the start time of the show (8pm). This allows us to solidify the order of the lineup beforehand and start the show on time.</li>
                    <li>Exhibitions are expected to be kept to 15 minutes max (Setup time is NOT included in this time). If you hit the time limit you will be given a two minute warning to wrap it up.</li>
                    <li>If for any reason you can't abide by the above rules you can reach out to me directly at least 48 hours before the start time of the show to try to work something out.</li>
                </ul>
                <p><strong>Failure to follow the above rules will result in you being unable to exhibit at future shows and may even put your attendance as an audience member at future shows in jeopardy if it causes too big of a headache to me personally.</strong></p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <form class="card-body" action="/shows/{{$show->id}}/submission-applications" method="POST">
        @csrf
        <h2 class="section-heading">Exhibit Details</h2>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label" for="name">Name</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name') }}" placeholder="First, Middle, Confirmation, Last" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="title">Exhibition Title</label>
                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" id="title" value="{{ old('title') }}" maxlength="50" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="phone">Phone</label>
                <input class="form-control @error('phone') is-invalid @enderror" type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Like loyalty... optional, but appreciated.">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label" for="email">Email</label>
                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Like loyalty... optional, but appreciated.">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="6" placeholder="If not covered by the title, let us know a little bit about what you're planning. Please also use this space to let us know of any requirements you might have technical or otherwise that you'll need for your exhibition (Mics, amps, projector, etc.). If you're not bringing it, list it! So we can make sure we have everything prepared!">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="d-grid d-sm-flex justify-content-sm-end mt-4">
            <button class="btn btn-success" type="submit">Submit Application</button>
        </div>
    </form>
</div>
@endsection
