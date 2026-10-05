@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">People</h3>
            <table class="table tabled-bordered dt-responsive no-wrap" id="peopleTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Shows Attended</th>
                        <th>Times Performed</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($people as $person)
                    <tr>
                        <td><a href="{{ route('people.show', $person) }}">{{ $person->name }}</a></td>
                        <td>{{ $person->email }}</td>
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
            $('#peopleTable').DataTable();
        });
    </script>
@endpush
