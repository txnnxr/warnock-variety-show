@extends('layouts.app')
@section('content')
    <div class="card p-3">
        <div class="row">
            <div class="col-12">
                <h2>What is the Warnock Variety Show?</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto, error exercitationem magni nemo non provident quae? Aperiam aspernatur atque consequuntur eos et, illum ipsa odit pariatur temporibus voluptate. Maiores, minus?</p>
            </div>
            <div class="col-6">
                <img src="/images/background.jpg" alt="" width="100%">
            </div>
        </div>
    </div>
    <div class="card p-3 my-2">
        <div class="row">
            <div class="col-12">
                <h2>Come!</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-6">Calendar
                <p>{{$show->date}}</p>
                {{Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($show->date), false)}}@if(Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($show->date), false) != 1) days @else day @endif until the next variety show!
                <a href="/shows/{{$show->id}}/view" class="btn btn-info form-control align-self-center">RSVP</a>
            </div>
            <div class="col-6">
                <h4>{{$show->name}}</h4>
                <p>{!! nl2br(substr($show->description, 0, 500)) !!}... <a href="/shows/{{$show->id}}/view">Read More</a></p>
            </div>
        </div>
    </div>
    <div class="card p-3 my-2">
        <div class="row">
            <div class="col-12"><h2>Perform!</h2></div>
        </div>
        <div class="row">
            <div class="col-6"><img src="/images/Red Icon Full .jpeg" alt="" width="100%"></div>
            <div class="col-6 align-content-center">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet autem cupiditate, delectus, dicta error excepturi explicabo, harum necessitatibus possimus qui quia reprehenderit velit voluptatum. Eius enim officia officiis qui ratione!</p>
                <a href="/shows/{{$show->id}}/submission-applications/create" class="btn btn-info form-control align-self-center">Exhibition Application</a></div>
        </div>
    </div>
@endsection
