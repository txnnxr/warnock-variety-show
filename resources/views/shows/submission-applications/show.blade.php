@extends('layouts.app')
@section('title', $submissionApplication->title)
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/shows/{{$submissionApplication->show->id}}/view">{{$submissionApplication->show->name}}</a></li>
            @can('admin')
                <li class="breadcrumb-item"><a href="/shows/{{$submissionApplication->show->id}}/submission-applications">Submissions</a></li>
            @endcan
            <li class="breadcrumb-item active" aria-current="page">{{$submissionApplication->title}}</li>
        </ol>
    </nav>
    @cannot('admin')
        <div class="alert alert-info">
            Thanks for applying! Bookmark this page to check on your application: <a href="{{ route('applications.status', $submissionApplication) }}" class="text-break">{{ route('applications.status', $submissionApplication) }}</a>
        </div>
    @endcannot
    <div class="card">
        <div class="card-body">
            <h1 class="card-heading">{{$submissionApplication->title}}</h1>
            <p class="text-center mb-4">
                <span class="status {{ $submissionApplication->approved ? 'status-attending' : 'status-waitlist' }}">{{$submissionApplication->getStatus()}}</span>
            </p>
            <dl class="row mb-0">
                <dt class="col-sm-3 form-label">Name</dt>
                <dd class="col-sm-9">
                    @can('admin')
                        @if($submissionApplication->person)
                            <a href="{{ route('people.show', $submissionApplication->person) }}">{{$submissionApplication->name}}</a>
                        @else
                            {{$submissionApplication->name}}
                        @endif
                    @else
                        {{$submissionApplication->name}}
                    @endcan
                </dd>
                <dt class="col-sm-3 form-label">Phone</dt>
                <dd class="col-sm-9">{{$submissionApplication->phone ?: '—'}}</dd>
                <dt class="col-sm-3 form-label">Email</dt>
                <dd class="col-sm-9 text-break">{{$submissionApplication->email ?: '—'}}</dd>
                <dt class="col-sm-3 form-label">Show</dt>
                <dd class="col-sm-9">{{$submissionApplication->show->name}}</dd>
                <dt class="col-sm-3 form-label">Description</dt>
                <dd class="col-sm-9">{!! nl2br(e(htmlspecialchars_decode((string) $submissionApplication->description))) !!}</dd>
            </dl>
            @can('admin')
                <div class="btn-group-actions justify-content-end mt-4">
                    <form method="POST" action="{{ route('applications.deny', $submissionApplication) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">Deny</button>
                    </form>
                    <form method="POST" action="{{ route('applications.approve', $submissionApplication) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Approve</button>
                    </form>
                </div>
            @endcan
        </div>
    </div>
@endsection
