@extends('layouts.app')
@section('content')
    <div>
        <a href="/shows/{{$submissionApplications->first()->show->id}}}/view">{{$submissionApplications->first()->show->name}}</a>
    </div>
<div class="card">
    <div class="row">
        <div class="col-12">
            <table class="table tabled-bordered dt-responsive no-wrap">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Show Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Buttons</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($submissionApplications as $submissionApplication)
                    <tr>
                        <td>{{$submissionApplication->name}}</td>
                        <td>{{$submissionApplication->phone}}</td>
                        <td>{{$submissionApplication->email}}</td>
                        <td>{{$submissionApplication->show->name}}</td>
                        <td>{{substr($submissionApplication->description, 0, 20)}}</td>
                        <td>{{$submissionApplication->getStatus()}}</td>
                        <td>
                            <a class="btn btn-secondary" href="/shows/{{$submissionApplication->show_id}}/submission-applications/{{$submissionApplication->id}}/view">View</a>
{{--                            <a class="btn btn-secondary" href="/shows/{{$submissionApplication->show_id}}/submission-applications/{{$submissionApplication->id}}/edit">Edit</a>--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
