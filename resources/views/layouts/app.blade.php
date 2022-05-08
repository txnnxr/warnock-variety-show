<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="/favicon.ico">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="antialiased">
        <div class="container ">
            <div class="row">
                <div class="col">
                   <h1 class="text-center my-3"><a href="/">Warnock Variety Show</a></h1>
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
{{--                    <div class="col"><a href="/rsvp">RSVP</a></div>--}}
{{--                    <div class="col"><a href="/mailing-list">Mailing List</a></div>--}}
                </div>
            @else
                <div class="row">
                    <a class ="col-1" href="{{ route('login') }}"> Login </a>
{{--                    <a class ="col-1" href="{{ route('register') }}">Register </a>--}}
                </div>
            @endif
            @yield('content')
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
