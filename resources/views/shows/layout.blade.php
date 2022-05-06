<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
{{--        --}}
        <link rel="icon" href="/favicon.ico">
        @stack('meta')
        <meta property="og:image" content="/images/background.jpg" />
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="antialiased">
        <div class="container ">
            <div class="row">
                <div class="col">
                   <h1 class="text-center my-3">
                       @if(Auth::user())<a href="/">Warnock Variety Show</a>@else Warnock Variety Show @endif</h1>
                </div>
            </div>
            @if(Auth::user())
                <div class="row">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Logout
                        </a>
                    </form>
                </div>
                <div class="row">
                    <div class="col"><a href="/shows">Shows</a></div>
                    <div class="col"><a href="/rsvp">RSVP</a></div>
                    <div class="col"><a href="/mailing-list">Mailing List</a></div>
                </div>
            @endif
            <div class="card p-3">
                <h2 class="card-title text-center mt-3">{{$show->name}}</h2>
                <div class="card-body mb-3">{!! nl2br($show->description) !!} </div>
            </div>
            @yield('shows-content')
       </div>
    <footer>
        <script
			  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
			  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
			  crossorigin="anonymous"></script>
        @stack('scripts')
    </footer>
    </body>
</html>
