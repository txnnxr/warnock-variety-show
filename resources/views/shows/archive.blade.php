@extends('layouts.app')
@section('title', 'Past Shows')
@section('content')
    <h1 class="card-heading">Past Shows</h1>
    @forelse($shows as $show)
        <article class="card">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12 @if($show->photos->isNotEmpty()) col-md-5 @endif">
                        <h2 class="h3 mb-1"><a href="{{ route('shows.show', $show) }}">{{ $show->name }}</a></h2>
                        <p class="show-meta text-start">{{ $show->date->format('l, F j, Y') }}</p>
                        @if($show->lineup->isNotEmpty())
                            <h3 class="h6 small-caps text-muted mb-1">Featuring</h3>
                            <p class="mb-0 fst-italic">{{ $show->lineup->pluck('exhibition_description')->join(' · ') }}</p>
                        @endif
                    </div>
                    @if($show->photos->isNotEmpty())
                        <div class="col-12 col-md-7">
                            <div class="photo-grid">
                                @foreach($show->photos->take(4) as $photo)
                                    <a href="{{ route('shows.show', $show) }}"><img src="{{ $photo->url }}" alt="{{ $photo->caption ?? $show->name }}" loading="lazy"></a>
                                @endforeach
                            </div>
                            @if($show->photos->count() > 4)
                                <a class="d-inline-block mt-2" href="{{ route('shows.show', $show) }}">See all {{ $show->photos->count() }} photos <i class="fa-solid fa-arrow-right"></i></a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </article>
    @empty
        <div class="card">
            <div class="card-body text-center fst-italic">No past shows yet.</div>
        </div>
    @endforelse
@endsection
