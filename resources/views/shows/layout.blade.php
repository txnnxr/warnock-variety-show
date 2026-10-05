@extends('layouts.app')
@section('title', $show->name)
@section('content')
    <div class="card playbill">
        <div class="card-body">
            <div class="ornament small-caps mb-2">Now Presenting</div>
            <h1 class="card-heading mb-1">{{ $show->name }}</h1>
            <p class="show-meta mb-0">{{ $show->date->format('l, F j, Y · g:ia') }}</p>
            @if($show->canceled)
                <p class="text-center mt-2"><span class="status status-requested">Canceled</span></p>
            @endif
            @if($show->description)
                <div class="show-description">{!! nl2br(e($show->description)) !!}</div>
            @endif
            @can('admin')
                <div class="btn-group-actions justify-content-center mt-4">
                    <a class="btn btn-sm btn-outline-secondary" href="/shows/{{$show->id}}/invite"><i class="fa-solid fa-envelope"></i> Invites</a>
                    <a class="btn btn-sm btn-outline-secondary" href="/shows/{{$show->id}}/submission-applications"><i class="fa-solid fa-scroll"></i> Submissions</a>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('lineup.index', $show) }}"><i class="fa-solid fa-list-ol"></i> Lineup</a>
                    <a class="btn btn-sm btn-outline-secondary" href="/shows/{{$show->id}}/edit"><i class="fa-solid fa-pen"></i> Edit</a>
                </div>
            @endcan
        </div>
    </div>
    @yield('shows-content')
@endsection
