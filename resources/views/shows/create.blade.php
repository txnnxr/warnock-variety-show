@extends('layouts.app')
@section('title', isset($show) ? "Edit {$show->name}" : 'New Show')
@section('content')
<div class="card">
    <form class="card-body" method="POST" action="{{ isset($show) ? "/shows/{$show->id}" : '/shows' }}">
        @csrf
        @isset($show)
            @method('PUT')
        @endisset
        <h1 class="card-heading">@isset($show) Edit {{$show->name}} @else Create a New Show @endisset</h1>
        <div class="row g-3">
            <div class="col-12 col-md-8">
                <label class="form-label" for="name">Name</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name', $show->name ?? '') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="date">Date &amp; Time</label>
                <input class="form-control @error('date') is-invalid @enderror" type="datetime-local" name="date" id="date" value="{{ old('date', isset($show) ? $show->date->format('Y-m-d\TH:i') : '') }}" required>
                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="6">{{ old('description', $show->description ?? '') }}</textarea>
            </div>
            <div class="col-12 col-md-8">
                <label class="form-label" for="address">Address</label>
                <input class="form-control @error('address') is-invalid @enderror" type="text" name="address" id="address" value="{{ old('address', $show->address ?? '') }}" required>
                <div class="form-text">Only shown to confirmed guests.</div>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="maxAttendants">Max Attendants</label>
                <input class="form-control @error('max_attendants') is-invalid @enderror" type="number" min="0" name="max_attendants" id="maxAttendants" value="{{ old('max_attendants', $show->max_attendants ?? 30) }}" required>
                <div class="form-text">Plus ones count. 0 means no limit.</div>
                @error('max_attendants')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="d-grid d-sm-flex justify-content-sm-end gap-2 mt-4">
            <a href="/shows" class="btn btn-outline-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">@isset($show) Save Changes @else Create Show @endisset</button>
        </div>
    </form>
</div>
@endsection
