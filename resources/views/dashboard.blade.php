<x-app-layout>
    <div class="card">
        <div class="card-body text-center">
            <h1 class="card-heading">Welcome back, {{ auth()->user()->name }}</h1>
            <p>You're logged in!</p>
            <div class="btn-group-actions justify-content-center">
                @can('admin')
                    <a href="/shows" class="btn btn-primary">Manage Shows</a>
                    <a href="{{ route('people.index') }}" class="btn btn-outline-secondary">People</a>
                @endcan
                <a href="{{ route('shows.archive') }}" class="btn btn-outline-secondary">Past Shows</a>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">Profile</a>
            </div>
        </div>
    </div>
</x-app-layout>
