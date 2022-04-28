<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
       <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div class="container ">
            <div class="row">
                <div class="col">
                   <h1 class="text-center"><a href="/">Warnock Variety Show</a></h1>
                </div>
            </div>
            <div class="row">
                <div class="col"><a href="/shows">Shows</a></div>
                <div class="col"><a href="/rsvp">RSVP</a></div>
                <div class="col"><a href="/mailing-list">Mailing List</a></div>
            </div>
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

