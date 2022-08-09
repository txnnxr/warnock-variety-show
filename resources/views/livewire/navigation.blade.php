<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
{{--    <div class="row">--}}
{{--        @if(Auth::user())--}}
{{--            <div class="col">--}}
{{--                <form method="POST" action="{{ route('logout') }}">--}}
{{--                    @csrf--}}
{{--                    <a href="route('logout')"--}}
{{--                       onclick="event.preventDefault();--}}
{{--                                                this.closest('form').submit();">--}}
{{--                        Logout--}}
{{--                    </a>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--            <div class="col">--}}
{{--                <a href="/shows">Shows</a>--}}
{{--            </div>--}}
{{--        @else--}}
{{--            <a class="col" href="{{ route('login') }}"> Login </a>--}}
{{--        @endif--}}
{{--        <div class="col">--}}
{{--            <a href="{!!action([\App\Http\Controllers\ShowController::class, 'show'], ['show' => \App\Models\Show::latest('date')->first()->id])!!}">Upcoming Show</a>--}}
{{--        </div>--}}
{{--    </div>--}}

            <!-- Navigation-->
        <a class="menu-toggle rounded" href="#"><i class="fas fa-bars"></i></a>
        <nav id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"><a href="#page-top">Start Bootstrap</a></li>
                <li class="sidebar-nav-item"><a href="#page-top">Home</a></li>
                <li class="sidebar-nav-item"><a href="#about">About</a></li>
                <li class="sidebar-nav-item"><a href="#services">Services</a></li>
                <li class="sidebar-nav-item"><a href="#portfolio">Portfolio</a></li>
                <li class="sidebar-nav-item"><a href="#contact">Contact</a></li>
            </ul>
        </nav>

</div>
