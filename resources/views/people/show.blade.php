@extends('layouts.app')
@section('content')
    <div>
        <a href="{{ route('people.index') }}">People</a>
    </div>
    <div class="card my-3">
        <div class="card-body">
            <h3 class="card-title">{{ $person->name }}</h3>
            <p class="mb-0">{{ $person->email }} @if($person->email && $person->phone_number) · @endif {{ $person->phone_number }}</p>
        </div>
    </div>
    <div class="card my-3">
        <div class="card-body">
            <h4 class="card-title">Invites</h4>
            @forelse($person->invites->sortByDesc('show.date') as $invite)
                <div>
                    <a href="{{ route('shows.show', $invite->show) }}">{{ $invite->show->name }}</a>
                    ({{ $invite->show->date->format('M j, Y') }}):
                    @if($invite->response_status == 'COWARD') MAYBE @else {{ $invite->response_status }} @endif
                    @if($invite->plus_one_status) (+1) @endif
                </div>
            @empty
                <p class="mb-0">No invites.</p>
            @endforelse
        </div>
    </div>
    <div class="card my-3">
        <div class="card-body">
            <h4 class="card-title">Performances</h4>
            @forelse($person->exhibitors->where('status', 'Approved')->sortByDesc('show.date') as $act)
                <div>
                    <a href="{{ route('shows.show', $act->show) }}">{{ $act->show->name }}</a>
                    ({{ $act->show->date->format('M j, Y') }}): {{ $act->exhibition_description }}
                </div>
            @empty
                <p class="mb-0">No performances.</p>
            @endforelse
        </div>
    </div>
    <div class="card my-3">
        <div class="card-body">
            <h4 class="card-title">Applications</h4>
            @forelse($person->submissionApplications->sortByDesc('show.date') as $application)
                <div>
                    <a href="/shows/{{ $application->show_id }}/submission-applications/{{ $application->id }}/view">{{ $application->title }}</a>
                    for {{ $application->show->name }}: {{ $application->getStatus() }}
                </div>
            @empty
                <p class="mb-0">No applications.</p>
            @endforelse
        </div>
    </div>
@endsection
