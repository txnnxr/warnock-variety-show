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
        @livewireStyles
    </head>
    <body class="antialiased">
        <div class="container">
            <h1 class="text-center my-3 site-title"><a href="/">Warnock Variety Show</a></h1>
            @livewire('navigation')
            <div class="card">
                <h3 class="card-title text-center mt-3">Login</h3>
                <form method="POST" action="{{ route('login') }}" class="card-body">
                    @csrf
                    <div class="form-group">
                        <!-- Email Address -->
                        <input id="email" class="form-control mb-3" type="email" name="email" required
                               autofocus placeholder="Email"/>

                        <!-- Password -->
                        <input id="password" class="form-control my-3"
                               type="password"
                               name="password"
                               required autocomplete="current-password"
                               placeholder="Password"/>
                    </div>
                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <button class="btn btn-primary" type="submit">
                            Login
                        </button>
                    </div>
                </form>
            </div>
       </div>
    <footer>
        <script
			  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
			  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
			  crossorigin="anonymous"></script>
        @stack('scripts')
    </footer>
    @livewireScripts
    </body>
</html>
