@extends('layouts.app')
@section('content')
    <div>
        <a href="/shows/{{$submissionApplication->show->id}}/view">{{$submissionApplication->show->name}}</a> @if(auth()->user()) と <a href="/shows/{{$submissionApplication->show->id}}/submission-applications">Submissions</a> @endif
    </div>
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
                        <td>{{substr($submissionApplication->description, 1, 20)}}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{$submissionApplication->getStatus()}}</td>
                    </tr>
                </table>
            </div>
        </div>
        @if(auth()->user())
            <div class="row">
                <div class="col-6" class="text-center">
                    <a class="btn btn-secondary" href="/submission-applications/{{$submissionApplication->id}}/deny">
                        <button>Deny</button>
                    </a>
                </div>
                <div class="col-6" class="text-center">
                    <a class="btn btn-secondary" href="/submission-applications/{{$submissionApplication->id}}/approve">
                        <button>Approve</button>
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
