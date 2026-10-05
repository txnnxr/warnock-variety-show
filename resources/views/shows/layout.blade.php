<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--        --}}
    <link rel="icon" href="/favicon.ico">
    @stack('meta')
    <meta property="og:image" content="/images/background.jpg"/>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->

    <!-- Scripts -->
    @vite(['resources/sass/app.sass', 'resources/js/app.js'])
</head>
<body class="antialiased">
<div class="container ">
    <div class="row">
        <div class="col">
            <h1 class="text-center my-3">
                <a href="/">Warnock Variety Show</a>
            </h1>
        </div>
    </div>
    @can('admin')
        <div class="row">
            <div class="col">
                <a href="/shows">Shows</a>
                <a href="{{ route('people.index') }}">People</a>
                <a href="{{ route('shows.archive') }}">Past Shows</a>
            </div>
            {{--                    <div class="col"><a href="/rsvp">RSVP</a></div>--}}
            {{--                    <div class="col"><a href="/mailing-list">Mailing List</a></div>--}}
            <div class="col">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="route('logout')"
                       onclick="event.preventDefault();
                                            this.closest('form').submit();">
                        Logout
                    </a>
                </form>
            </div>

        </div>
    @else
        <div class="row">
            <div class="col">
                <a href="{{ route('shows.archive') }}">Past Shows</a>
                @guest
                    <a href="{{ route('login') }}">Login</a>
                @endguest
            </div>
        </div>
    @endcan
    <div class="card p-3">
        <h2 class="card-title text-center mt-3">{{$show->name}}</h2>
        <div class="card-body mb-3">
            <p>{!! nl2br($show->description) !!}</p>
            @can('admin')
                <div class="row">
                    <div class="col">
                        <a href="/shows/{{$show->id}}/invite">Invites</a>
                        <a href="/shows/{{$show->id}}/submission-applications">View Submissions</a>
                        <a href="{{ route('lineup.index', $show) }}">Lineup</a>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @yield('shows-content')
</div>
<footer>
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    @stack('scripts')
</footer>
</body>
</html>
