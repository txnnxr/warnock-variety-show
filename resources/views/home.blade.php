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
                <p>Step into the vibrant world of the Warnock Variety Show, where creativity knows (almost) no bounds! This unique event is not just a show; it's a free-spirited celebration of
                    artistic expression in a cozy, house party atmosphere. Artists of all genres are invited to showcase their talents to an intimate crowd.</p>
                <p>This participatory extravaganza is an open invitation for artists to apply, share, test out new ideas, and connect with an engaged audience.</p>
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
                <h2>Exhibit!</h2>
                <h4>{{Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($show->date)->addDays(-5), false)}}@if(Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($show->date), false) != 1)
                        days
                    @else
                        day
                    @endif until the submission deadline for the next variety show!</h4>

                <p></p>

                <p>We're pretty broad about what we allow at the show we've had: tap dancing, fire dancing, poetry readings, artist show & tells, technical talks, musical performances of all kinds! If
                    you're not sure if what you do belongs here, it probably does! </p>
                {{--(EXCEPT KARAOKE)--}}
                <p>We aim to have about 6 artists with 10 minute exhibition slots per show. So submit an application early if you want to get in for the next show! </p>

                <a href="/shows/{{$show->id}}/submission-applications/create" class="btn btn-info form-control align-self-center">Exhibition Application</a>
            </div>
        </div>
    </div>
@endsection
