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

    @if($others->isNotEmpty())
        <div class="card mt-4">
            <div class="card-body">
                <h2 class="section-heading">Merge a Duplicate</h2>
                <p>If the same person shows up twice, merge the duplicate into {{ $person->name }}. Their invites, applications and performances move here, missing contact details are filled in, and the duplicate is removed.</p>
                <form method="POST" action="{{ route('people.merge', $person) }}" class="row g-3 align-items-end" x-data="{ sure: false }">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="duplicate_id">Duplicate</label>
                        <select class="form-select @error('duplicate_id') is-invalid @enderror" id="duplicate_id" name="duplicate_id" required>
                            <option value="">Choose a person…</option>
                            @foreach($others as $other)
                                <option value="{{ $other->id }}" @selected(old('duplicate_id') == $other->id)>{{ $other->name }}@if($other->email) ({{ $other->email }})@elseif($other->phone_number) ({{ $other->phone_number }})@endif</option>
                            @endforeach
                        </select>
                        @error('duplicate_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="confirm-merge" x-model="sure" required>
                            <label class="form-check-label" for="confirm-merge">Yes, merge them into {{ $person->name }}</label>
                        </div>
                        <button type="submit" class="btn btn-primary" x-bind:disabled="! sure">Merge</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
