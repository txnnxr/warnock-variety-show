@extends('layouts.app')
@section('content')
    <div class="card playbill">
        <div class="card-body">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-md-7 order-2 order-md-1">
                    <h1 class="card-heading text-md-start">What is the Warnock Variety Show?</h1>
                    <p>Step into the vibrant world of the Warnock Variety Show, where creativity knows (almost) no bounds! This unique event is not just a show; it's a free-spirited celebration of
                        artistic expression in a cozy, house party atmosphere. Artists of all genres are invited to showcase their talents to an intimate crowd.</p>
                    <p class="mb-0">This participatory extravaganza is an open invitation for artists to apply, share, test out new ideas, and connect with an engaged audience.</p>
                </div>
                <div class="col-12 col-md-5 order-1 order-md-2 text-center">
                    <img src="/images/background.jpg" alt="Watercolor of a dog and a cat in green robes, the Warnock Variety Show poster" class="img-fluid shadow-sm" style="max-height: 420px;">
                </div>
            </div>
        </div>
    </div>
    @if($show)
    @php
        $daysUntilShow = (int) Carbon\Carbon::now()->diffInDays($show->date, false);
        $daysUntilDeadline = (int) Carbon\Carbon::now()->diffInDays($show->date->copy()->addDays(-5), false);
    @endphp
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body d-flex flex-column text-center">
                    <h2 class="section-heading">Come!</h2>
                    <div class="countdown">{{ max($daysUntilShow, 0) }}</div>
                    <div class="countdown-label mb-3">{{ $daysUntilShow == 1 ? 'day' : 'days' }} to go</div>
                    <h3 class="h4 mb-1">{{ $show->name }}</h3>
                    <p class="show-meta">{{ $show->date->format('l, F j, Y') }}</p>
                    <p class="text-start">{!! nl2br(e(Str::limit($show->description, 330))) !!} <a href="/shows/{{$show->id}}/view">Read more</a></p>
                    <a href="/shows/{{$show->id}}/view" class="btn btn-primary mt-auto">RSVP</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body d-flex flex-column text-center">
                    <h2 class="section-heading">Exhibit!</h2>
                    @if($daysUntilDeadline >= 0)
                        <div class="countdown">{{ $daysUntilDeadline }}</div>
                        <div class="countdown-label mb-3">{{ $daysUntilDeadline == 1 ? 'day' : 'days' }} until the submission deadline</div>
                    @else
                        <p class="countdown-label mb-3">The submission deadline has passed, but you can still apply.</p>
                    @endif
                    <p class="text-start">We're pretty broad about what we allow at the show we've had: tap dancing, fire dancing, poetry readings, artist show & tells, technical talks, sound baths, musical performances of all kinds! If
                        you're not sure if what you do belongs here, it probably does! </p>
                    {{--(EXCEPT KARAOKE)--}}
                    <p class="text-start">We aim to have about 6 artists with 10 minute exhibition slots per show. So submit an application early if you want to get in for the next show! </p>
                    <a href="/shows/{{$show->id}}/submission-applications/create" class="btn btn-success mt-auto">Exhibition Application</a>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="card">
            <div class="card-body text-center">
                <h2 class="section-heading">Intermission</h2>
                <p>The next show hasn't been announced yet. Check back soon, or relive the <a href="{{ route('shows.archive') }}">past shows</a>.</p>
            </div>
        </div>
    @endif
@endsection
