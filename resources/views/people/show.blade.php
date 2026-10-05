@extends('layouts.app')
@section('title', $person->name)
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('people.index') }}">People</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $person->name }}</li>
        </ol>
    </nav>
    <div class="card playbill">
        <div class="card-body text-center">
            <h1 class="card-heading mb-1">{{ $person->name }}</h1>
            <p class="mb-0 text-break">{{ $person->email }} @if($person->email && $person->phone_number) · @endif {{ $person->phone_number }}</p>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="section-heading">Invites</h2>
                    <ul class="guest-list">
                        @forelse($person->invites->sortByDesc('show.date') as $invite)
                            <li class="flex-wrap">
                                <a href="{{ route('shows.show', $invite->show) }}">{{ $invite->show->name }}</a>
                                <span class="text-muted small">{{ $invite->show->date->format('M j, Y') }}</span>
                                <x-rsvp-status :invite="$invite" />
                            </li>
                        @empty
                            <li class="text-muted fst-italic">No invites.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="section-heading">Performances</h2>
                    <ul class="guest-list">
                        @forelse($person->exhibitors->where('status', 'Approved')->sortByDesc('show.date') as $act)
                            <li class="flex-wrap">
                                <span class="fst-italic">{{ $act->exhibition_description }}</span>
                                <a href="{{ route('shows.show', $act->show) }}" class="small">{{ $act->show->name }}</a>
                            </li>
                        @empty
                            <li class="text-muted fst-italic">No performances.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="section-heading">Applications</h2>
                    <ul class="guest-list">
                        @forelse($person->submissionApplications->sortByDesc('show.date') as $application)
                            <li class="flex-wrap">
                                <a href="/shows/{{ $application->show_id }}/submission-applications/{{ $application->id }}/view">{{ $application->title }}</a>
                                <span class="small text-muted">{{ $application->show->name }}</span>
                                <span class="status {{ $application->approved ? 'status-attending' : 'status-waitlist' }}">{{ $application->getStatus() }}</span>
                            </li>
                        @empty
                            <li class="text-muted fst-italic">No applications.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
