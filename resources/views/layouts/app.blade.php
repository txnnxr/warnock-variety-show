<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') · @endif Warnock Variety Show</title>
    <link rel="icon" href="/favicon.ico">

    {{-- Link previews (iMessage, Instagram, Slack, etc.) --}}
    <meta name="description" content="@yield('description', 'A free-spirited celebration of artistic expression in a cozy, house party atmosphere.')">
    <meta property="og:site_name" content="Warnock Variety Show">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', 'Warnock Variety Show')">
    <meta property="og:description" content="@yield('description', 'A free-spirited celebration of artistic expression in a cozy, house party atmosphere.')">
    <meta property="og:image" content="@yield('og_image', url('/images/background.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    @stack('meta')

    @vite(['resources/sass/app.sass', 'resources/js/app.js'])
</head>
<body>
<header class="masthead">
    <p class="masthead-title"><a href="/">Warnock Variety Show</a></p>
    <p class="masthead-tagline">✦ A house party of variety ✦</p>
</header>

<nav class="navbar navbar-expand-md site-nav">
    <div class="container">
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
            Menu <i class="fa-solid fa-bars ms-1"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="siteNav">
            <ul class="navbar-nav align-items-md-center gap-md-3">
                <li class="nav-item"><a class="nav-link @if(request()->is('/')) active @endif" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('shows.archive')) active @endif" href="{{ route('shows.archive') }}">Past Shows</a></li>
                @can('admin')
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('shows.index')) active @endif" href="/shows">Manage Shows</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('people.*')) active @endif" href="{{ route('people.index') }}">People</a></li>
                @endcan
                @auth
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('profile.edit')) active @endif" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">Log Out</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('login')) active @endif" href="{{ route('login') }}">Log In</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container">
    @if(session('status') && ! in_array(session('status'), ['profile-updated', 'password-updated', 'verification-link-sent']))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @isset($header)
        <div class="mb-3">{{ $header }}</div>
    @endisset
    @yield('content')
    @isset($slot)
        {{ $slot }}
    @endisset
</main>

<footer class="site-footer">
    <div class="ornament mb-2"><i class="fa-solid fa-otter"></i></div>
    Warnock Variety Show · <a href="{{ route('shows.archive') }}">Past Shows</a>
</footer>

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
</body>
</html>
