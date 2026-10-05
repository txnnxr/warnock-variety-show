@extends('layouts.app')
@section('content')
    <div>
        <a href="/shows/{{$submissionApplication->show->id}}/view">{{$submissionApplication->show->name}}</a> @can('admin') と <a href="/shows/{{$submissionApplication->show->id}}/submission-applications">Submissions</a> と <a href="{{ route('lineup.index', $submissionApplication->show) }}">Lineup</a> @endcan
    </div>
    @cannot('admin')
        <div class="alert alert-info">
            Thanks for applying! Bookmark this page to check on your application: <a href="{{ route('applications.status', $submissionApplication) }}">{{ route('applications.status', $submissionApplication) }}</a>
        </div>
    @endcannot
    <div class="card">
        <div class="row">
            <div class="col-12">
                <table class="table tabled-bordered dt-responsive no-wrap">

                    <tr>
                        <td>Name</td>
                        <td>{{$submissionApplication->name}}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{$submissionApplication->phone}}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{$submissionApplication->email}}</td>
                    </tr>
                    <tr>
                        <th>Show Name</th>
                        <td>{{$submissionApplication->show->name}}</td>
                    </tr>
                    <tr>
                        <td>Title</td>
                        <td>{{$submissionApplication->title}}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{htmlspecialchars_decode($submissionApplication->description)}}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{$submissionApplication->getStatus()}}</td>
                    </tr>
                </table>
            </div>
        </div>
        @can('admin')
            <div class="row">
                <div class="col-6 text-center">
                    <form method="POST" action="{{ route('applications.deny', $submissionApplication) }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Deny</button>
                    </form>
                </div>
                <div class="col-6 text-center">
                    <form method="POST" action="{{ route('applications.approve', $submissionApplication) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Approve</button>
                    </form>
                </div>
            </div>
        @endcan
    </div>
@endsection
