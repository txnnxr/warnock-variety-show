@extends('layouts.app')
@section('content')
    <div>
        <a href="{{ route('shows.show', $show) }}">{{ $show->name }}</a> と <a href="/shows/{{ $show->id }}/submission-applications">Submissions</a>
    </div>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Lineup</h3>
            @if($lineup->isEmpty())
                <p>No approved acts yet. Approving an application adds it to the end of the lineup.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Performer</th>
                            <th>Act</th>
                            <th>Order</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($lineup as $act)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="{{ route('people.show', $act->person) }}">{{ $act->person->name }}</a></td>
                            <td>
                                @if($act->submission_application_id)
                                    <a href="/shows/{{ $show->id }}/submission-applications/{{ $act->submission_application_id }}/view">{{ $act->exhibition_description }}</a>
                                @else
                                    {{ $act->exhibition_description }}
                                @endif
                            </td>
                            <td>
                                @unless($loop->first)
                                    <form class="d-inline-block" method="POST" action="{{ route('lineup.up', $act) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary" aria-label="Move up">↑</button>
                                    </form>
                                @endunless
                                @unless($loop->last)
                                    <form class="d-inline-block" method="POST" action="{{ route('lineup.down', $act) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary" aria-label="Move down">↓</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
