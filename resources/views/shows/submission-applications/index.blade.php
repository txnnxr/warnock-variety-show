@extends('layouts.app')
@section('title', 'Submissions')
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/shows/{{$show->id}}/view">{{$show->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Submissions</li>
            <li class="ms-auto"><a href="{{ route('lineup.index', $show) }}">Lineup <i class="fa-solid fa-arrow-right"></i></a></li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-body">
            <h1 class="card-heading">Submissions</h1>
            @if($submissionApplications->isEmpty())
                <p class="text-center fst-italic mb-0">No applications yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Act</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($submissionApplications as $submissionApplication)
                            <tr>
                                <td>{{$submissionApplication->title}}</td>
                                <td>{{$submissionApplication->name}}</td>
                                <td class="text-break">{{$submissionApplication->email ?: $submissionApplication->phone}}</td>
                                <td>{{ Str::limit(htmlspecialchars_decode((string) $submissionApplication->description), 40) }}</td>
                                <td><span class="status {{ $submissionApplication->approved ? 'status-attending' : 'status-waitlist' }}">{{$submissionApplication->getStatus()}}</span></td>
                                <td><a class="btn btn-sm btn-outline-secondary" href="/shows/{{$submissionApplication->show_id}}/submission-applications/{{$submissionApplication->id}}/view">View</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
