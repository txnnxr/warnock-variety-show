@extends('app')
@section('content')
    <div class="card">
        <div class="card-body p-3 py-5">
            {{--            <p class="p-3">--}}
            {{--
                TODO: Populate this description from the database
                On login determine which site_details to use based on subdomain
                New Table: site_details (
                subdomain
                title
                description
             --}}
            {{--                This show is a silly idea run by a silly person that has an excess of talented friends.--}}
            {{--            </p>--}}
            <div class="text-center">
                <a href="{!!action([\App\Http\Controllers\ShowController::class, 'show'], ['show' => \App\Models\Show::latest('date')->first()->id])!!}" class="btn btn-primary btn-lg p-4">Upcoming
                    Show</a>
            </div>
        </div>
    </div>
    <style>
        .hideable-nav {
            display: none;
        }
    </style>
@endsection
