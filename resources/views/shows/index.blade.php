@extends('layouts.app')
@section('content')
    @foreach($shows as $show)
        <div class="row">
            <div class="col-3">{{$show->name}}</div>
            <div class="col-3">{{$show->date}}</div>
            <div class="col-3">{{$show->address}}</div>
            <div class="col-3"><a href="/shows/{{$show->id}}/invite">Invite</a><a href="/shows/{{$show->id}}">View</a></div>
        </div>
    @endforeach
@endsection
