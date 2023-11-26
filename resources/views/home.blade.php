@extends('layouts.app')
@section('content')
    <div class="card p-3">
        <div class="row">
            <div class="col-12">
                <h2>What is the Warnock Variety Show?</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-8">
                <p>Step into the vibrant world of the Warnock Variety Show, where creativity knows no bounds! This unique event is not just a show; it's a free-spirited celebration of artistic expression in a cozy, house party atmosphere. Artists of all genres are invited to showcase their talents to an intimate crowd, turning the venue into a dynamic canvas of diverse creations.</p>
<p> Imagine a living room transformed into a gallery, a backyard stage for performances, and the sweet hum of collaboration filling the air. This participatory extravaganza is an open invitation for artists to apply, share, and connect with an engaged audience eager to explore the richness of local talent.</p>
<p>Join us for a night of free-spirited revelry, where the lines between creator and audience blur, fostering a sense of community and shared appreciation for the arts. The Warnock Variety Show is not just an event; it's a celebration of creativity, uniting artists and enthusiasts in an unforgettable, house-party-like experience. Be part of the magic, where every corner holds a new discovery and every artist finds a home in the heart of the crowd.</p>
            </div>
            <div class="col-12 col-md-4">
                <img src="/images/background.jpg" alt="" width="100%">
            </div>
        </div>
    </div>
    <div class="row" style="--bs-gutter-x: 0;">
        <div class="col-12 col-md-6 pe-0 pe-sm-1">
            <div class="card card-alt p-3 my-2">
                <h2>Come!</h2>
                <h3>{{$show->date->format('l, F j, Y')}}</h3>
                <h4>{{Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($show->date), false)}}@if(Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($show->date), false) != 1)
                        days
                    @else
                        day
                    @endif!</h4>
                <h5>{{$show->name}}</h5>
                <p>{!! nl2br(substr($show->description, 0, 330)) !!}... <a href="
            /shows/{{$show->id}}/view">Read More</a></p>
                <a href="/shows/{{$show->id}}/view" class="btn btn-info form-control align-self-center">RSVP</a>
            </div>
        </div>
        <div class="col-12 col-md-6 ps-0 ps-sm-1">
        <div class="card card-alt p-3 my-2">
            <h2>Perform!</h2>
            <h4>{{Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($show->date)->addDays(-5), false)}}@if(Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($show->date), false) != 1)
                    days
                @else
                    day
                @endif until the submission deadline for the next variety show!</h4>
            {{--                <h4>{{$show->name}}</h4>--}}
            {{--                <p>{!! nl2br(substr($show->description, 0, 500)) !!}... <a href="/shows/{{$show->id}}/view">Read More</a></p>--}}


            <p>We're pretty broad about what we allow at the show we've had: tap dancing, fire dancing, poetry readings, artist show & tells, technical talks, musical performances of all kinds! If you're not sure if what you do belongs here, it probably does! (EXCEPT KARAOKE)</p>

            <p>We aim to have 5-6 artists with 10 minute exhibition slots per show. So submit an application early if you want to get in for the next show! </p>



            <a href="/shows/{{$show->id}}/submission-applications/create" class="btn btn-info form-control align-self-center">Exhibition Application</a>
        </div>
    </div>
    </div>

    {{--    <div class="card card-alt-2 p-3 my-2">--}}
    {{--        <div class="row">--}}
    {{--            <div class="col-12"><h2>Perform!</h2></div>--}}
    {{--        </div>--}}
    {{--        <div class="row">--}}
    {{--            <div class="col-6">--}}
    {{--                --}}{{--                <img src="/images/Red Icon Full .jpeg" alt="" width="100%">--}}
    {{--                <div id="carouselExampleIndicators" class="carousel slide" data-interval="0" data-bs-ride="carousel">--}}
    {{--                      --}}
    {{--                    <div class="carousel-indicators">--}}
    {{--                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>--}}
    {{--                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>--}}
    {{--                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>--}}
    {{--                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>--}}
    {{--                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>--}}
    {{--                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5" aria-label="Slide 6"></button>--}}
    {{--                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="6" aria-label="Slide 7"></button>--}}
    {{--                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="7" aria-label="Slide 8"></button>--}}
    {{--                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="8" aria-label="Slide 9"></button>--}}
    {{--                    </div>--}}
    {{--                    <div class="carousel-inner">--}}
    {{--                        <div class="carousel-item active">--}}
    {{--                            <img src="/images/IMG_0007.jpg" class="d-block w-100" alt="...">--}}
    {{--                        </div>--}}
    {{--                        <div class="carousel-item">--}}
    {{--                            <img src="/images/IMG_1279.jpg" class="d-block w-100" alt="...">--}}
    {{--                        </div>--}}
    {{--                        <div class="carousel-item">--}}
    {{--                            <img src="/images/IMG_1492.JPG" class="d-block w-100" alt="...">--}}
    {{--                        </div>--}}
    {{--                        <div class="carousel-item">--}}
    {{--                            <img src="/images/IMG_1494.JPG" class="d-block w-100" alt="...">--}}
    {{--                        </div>--}}
    {{--                        <div class="carousel-item">--}}
    {{--                            <img src="/images/IMG_1505.JPG" class="d-block w-100" alt="...">--}}
    {{--                        </div>--}}
    {{--                        <div class="carousel-item">--}}
    {{--                            <img src="/images/IMG_2353.JPG" class="d-block w-100" alt="...">--}}
    {{--                        </div>--}}
    {{--                        <div class="carousel-item">--}}
    {{--                            <img src="/images/IMG_2373.JPG" class="d-block w-100" alt="...">--}}
    {{--                        </div>--}}
    {{--                        <div class="carousel-item">--}}
    {{--                            <img src="/images/IMG_2406.JPG" class="d-block w-100" alt="...">--}}
    {{--                        </div>--}}
    {{--                        <div class="carousel-item">--}}
    {{--                            <img src="/images/IMG_9245.jpg" class="d-block w-100" alt="...">--}}
    {{--                        </div>--}}

    {{--                    </div>--}}
    {{--                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">--}}
    {{--                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>--}}
    {{--                        <span class="visually-hidden">Previous</span>--}}
    {{--                    </button>--}}
    {{--                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">--}}
    {{--                        <span class="carousel-control-next-icon" aria-hidden="true"></span>--}}
    {{--                        <span class="visually-hidden">Next</span>--}}
    {{--                    </button>--}}
    {{--                </div>--}}
    {{--            </div>--}}

    {{--        </div>--}}
    {{--    </div>--}}
@endsection
