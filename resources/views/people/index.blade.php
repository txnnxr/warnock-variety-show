@extends('layouts.app')
@section('title', 'People')
@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-heading">People</h1>
            <table class="table dt-responsive w-100" id="peopleTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Attended</th>
                        <th>Performed</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($people as $person)
                    <tr>
                        <td><a href="{{ route('people.show', $person) }}">{{ $person->name }}</a></td>
                        <td class="text-break">{{ $person->email }}</td>
                        <td>{{ $person->phone_number }}</td>
                        <td>{{ $person->attended_count }}</td>
                        <td>{{ $person->performed_count }}</td>
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
            $('#peopleTable').DataTable({ responsive: true, pageLength: 25 });
        });
    </script>
@endpush
