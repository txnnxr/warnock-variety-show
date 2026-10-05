<x-app-layout>
    <h1 class="card-heading">Welcome back, {{ auth()->user()->name }}</h1>

    @can('admin')
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="section-heading">Next Show</h2>
                        @if($next)
                            <h3 class="h4 text-center mb-1"><a href="{{ route('shows.show', $next) }}">{{ $next->name }}</a></h3>
                            <p class="show-meta mb-3">{{ $next->date->format('l, F j · g:ia') }} · {{ (int) now()->diffInDays($next->date) }} days away</p>
                            <div class="row row-cols-2 row-cols-sm-4 g-3 mb-3">
                                <div class="col stat"><div class="stat-value">{{ $next->seatsTaken() }}@if($next->max_attendants > 0)<span class="fs-6 text-muted">/{{ $next->max_attendants }}</span>@endif</div><div class="stat-label">Seats</div></div>
                                <div class="col stat"><div class="stat-value">{{ count($next->waitlist_invites) }}</div><div class="stat-label">Waitlist</div></div>
                                <div class="col stat"><div class="stat-value">{{ count($next->maybe_invites) }}</div><div class="stat-label">Maybe</div></div>
                                <div class="col stat"><div class="stat-value">{{ $next->lineup()->count() }}</div><div class="stat-label">Acts</div></div>
                            </div>
                            <div class="btn-group-actions justify-content-center">
                                <a href="{{ route('invites.index', $next) }}" class="btn btn-sm btn-primary">Invites</a>
                                <a href="/shows/{{ $next->id }}/submission-applications" class="btn btn-sm btn-outline-secondary">Submissions</a>
                                <a href="{{ route('lineup.index', $next) }}" class="btn btn-sm btn-outline-secondary">Lineup</a>
                            </div>
                        @else
                            <p class="text-center">No upcoming show yet.</p>
                            <div class="text-center"><a href="/shows/create" class="btn btn-success">Plan the Next Show</a></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="section-heading">To Do</h2>
                        @if(empty($todos))
                            <p class="text-center fst-italic mb-0">All caught up.</p>
                        @else
                            <ul class="guest-list">
                                @foreach($todos as $todo)
                                    <li class="justify-content-between">
                                        <span>{{ $todo['label'] }}</span>
                                        <a href="{{ $todo['url'] }}" class="btn btn-sm btn-outline-secondary text-nowrap">{{ $todo['action'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center">
                <p>You're logged in!</p>
                <a href="{{ route('shows.archive') }}" class="btn btn-outline-secondary">Past Shows</a>
            </div>
        </div>
    @endcan
</x-app-layout>
