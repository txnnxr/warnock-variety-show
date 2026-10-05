@extends('layouts.app')
@section('content')
    <h2 class="text-center my-3">Past Shows</h2>
    @forelse($shows as $show)
        <div class="card my-3">
            <div class="card-body">
                <h3 class="card-title"><a href="{{ route('shows.show', $show) }}">{{ $show->name }}</a></h3>
                <h5>{{ $show->date->format('l, F j, Y') }}</h5>
                @if($show->lineup->isNotEmpty())
                    <p class="mb-1"><strong>Lineup:</strong> {{ $show->lineup->pluck('exhibition_description')->join(', ') }}</p>
                @endif
                @if($show->photos->isNotEmpty())
                    <div class="row g-2 mt-1">
                        @foreach($show->photos->take(4) as $photo)
                            <div class="col-6 col-md-3">
                                <a href="{{ route('shows.show', $show) }}"><img src="{{ $photo->url }}" class="img-fluid rounded" alt="{{ $photo->caption ?? $show->name }}" loading="lazy"></a>
                            </div>
                        @endforeach
                    </div>
                    @if($show->photos->count() > 4)
                        <a href="{{ route('shows.show', $show) }}">See all {{ $show->photos->count() }} photos</a>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <p class="text-center">No past shows yet.</p>
    @endforelse
@endsection
