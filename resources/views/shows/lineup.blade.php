@extends('layouts.app')
@section('title', "Lineup · {$show->name}")
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shows.show', $show) }}">{{ $show->name }}</a></li>
            <li class="breadcrumb-item"><a href="/shows/{{ $show->id }}/submission-applications">Submissions</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lineup</li>
        </ol>
    </nav>
    <div class="card playbill">
        <div class="card-body">
            <h1 class="card-heading">The Lineup</h1>
            @if($lineup->isEmpty())
                <p class="text-center fst-italic mb-0">No approved acts yet. Approving an application adds it to the end of the lineup.</p>
            @else
                <ol class="lineup">
                    @foreach($lineup as $act)
                        <li>
                            <span class="act">
                                @if($act->submission_application_id)
                                    <a href="/shows/{{ $show->id }}/submission-applications/{{ $act->submission_application_id }}/view">{{ $act->exhibition_description }}</a>
                                @else
                                    {{ $act->exhibition_description }}
                                @endif
                            </span>
                            <span class="leader" aria-hidden="true"></span>
                            <span class="performer"><a href="{{ route('people.show', $act->person) }}">{{ $act->person->name }}</a></span>
                            <span class="lineup-controls d-inline-flex gap-1">
                                <form method="POST" action="{{ route('lineup.up', $act) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" aria-label="Move {{ $act->exhibition_description }} up" @disabled($loop->first)><i class="fa-solid fa-arrow-up"></i></button>
                                </form>
                                <form method="POST" action="{{ route('lineup.down', $act) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" aria-label="Move {{ $act->exhibition_description }} down" @disabled($loop->last)><i class="fa-solid fa-arrow-down"></i></button>
                                </form>
                            </span>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
@endsection
