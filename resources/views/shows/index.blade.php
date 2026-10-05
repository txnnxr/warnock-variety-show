@extends('layouts.app')
@section('title', 'Manage Shows')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h1 class="card-heading mb-0">Shows</h1>
                <a href="/shows/create" class="btn btn-success"><i class="fa-solid fa-plus"></i> New Show</a>
            </div>
            <div class="table-responsive">
                <table class="table" id="showsTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Seats</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($shows as $show)
                        <tr>
                            <td><a href="/shows/{{$show->id}}/view">{{$show->name}}</a></td>
                            <td data-order="{{ $show->date->timestamp }}" class="text-nowrap">{{ $show->date->format('M j, Y · g:ia') }}</td>
                            <td>{{ $show->seatsTaken() }}@if($show->max_attendants > 0) / {{ $show->max_attendants }}@endif</td>
                            <td class="text-nowrap text-end">
                                <a class="btn btn-sm btn-primary" href="/shows/{{$show->id}}/invite">Invites</a>
                                <a class="btn btn-sm btn-outline-secondary" href="/shows/{{$show->id}}/edit">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#showsTable').DataTable({ order: [[1, 'desc']] });
        });
    </script>
@endpush
