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
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#showsTable').DataTable();
        });
    </script>
@endpush
