@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <a href="/shows/create" class="btn btn-info form-control align-self-center">Create Show</a>
            <table class="table tabled-bordered dt-responsive no-wrap" id="showsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Buttons</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $index = 1;
                @endphp
                @foreach($shows as $show)
                    <tr>
                        <td>{{$show->name}}</td>
                        <td>{{$show->date}}</td>
                        <td>
                            <a class="btn btn-primary"href="/shows/{{$show->id}}/invite">Invite</a>
                            <a class="btn btn-secondary"href="/shows/{{$show->id}}/view">View</a>
                            <a class="btn btn-secondary"href="/shows/{{$show->id}}/edit">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#showsTable').DataTable();
        });
    </script>
@endpush
