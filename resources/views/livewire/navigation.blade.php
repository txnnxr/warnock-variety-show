<nav>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="row">
        @if(Auth::user())
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
            <div class="col">
                <a href="/shows">Shows</a>
            </div>
        @else
            <a class="col" href="{{ route('login') }}"> Login </a>
        @endif
        <div class="col hideable-nav">
            <a href="{!!action([\App\Http\Controllers\ShowController::class, 'show'], ['show' => \App\Models\Show::latest('date')->first()->id])!!}">Upcoming Show</a>
        </div>
    </div>

</nav>
