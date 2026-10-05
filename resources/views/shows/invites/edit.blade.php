@extends('shows.layout')
@section('shows-content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('invites.index', $show) }}">Invites</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $invite->full_name }}</li>
        </ol>
    </nav>
    <div class="card">
        <form class="card-body" method="POST" action="{{ route('invites.update', $invite) }}">
            @csrf
            @method('PUT')
            <h2 class="section-heading">Edit Invite</h2>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label" for="first_name">First name</label>
                    <input class="form-control @error('first_name') is-invalid @enderror" type="text" id="first_name" name="first_name" value="{{ old('first_name', $invite->first_name) }}" required>
                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="middle_name">Middle name</label>
                    <input class="form-control" type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $invite->middle_name) }}">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="last_name">Last name</label>
                    <input class="form-control" type="text" id="last_name" name="last_name" value="{{ old('last_name', $invite->last_name) }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email', $invite->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="phone">Phone</label>
                    <input class="form-control" type="tel" id="phone" name="phone" value="{{ old('phone', $invite->phone) }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="response_status">Response</label>
                    <select class="form-select" id="response_status" name="response_status">
                        @foreach(\App\Models\Invite::STATUSES as $value => $label)
                            <option value="{{ $value }}" @selected(old('response_status', $invite->response_status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Setting someone to Attending works even when the show is full.</div>
                </div>
                <div class="col-12 col-md-6">
                    <fieldset class="choice-group h-100">
                        <legend>Options</legend>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="talent" name="talent" value="1" @checked(old('talent', $invite->talent))>
                            <label class="form-check-label" for="talent">Has a talent</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_plus_one_option" name="has_plus_one_option" value="1" @checked(old('has_plus_one_option', $invite->has_plus_one_option))>
                            <label class="form-check-label" for="has_plus_one_option">Allowed a plus one</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="plus_one_status" name="plus_one_status" value="1" @checked(old('plus_one_status', $invite->plus_one_status))>
                            <label class="form-check-label" for="plus_one_status">Bringing a plus one</label>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="d-grid d-sm-flex justify-content-sm-end gap-2 mt-4">
                <a href="{{ route('invites.index', $show) }}" class="btn btn-outline-secondary">Cancel</a>
                <button class="btn btn-primary" type="submit">Save Invite</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="section-heading">Remove Invite</h2>
            <p>Removes {{ $invite->first_name }} from this show. If they had a seat, the next person on the waitlist moves in. The record is kept in the database for your history.</p>
            <form method="POST" action="{{ route('invites.destroy', $invite) }}" x-data="{ sure: false }">
                @csrf
                @method('DELETE')
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirm-remove" x-model="sure" required>
                    <label class="form-check-label" for="confirm-remove">Yes, remove {{ $invite->full_name }}</label>
                </div>
                <button type="submit" class="btn btn-danger" x-bind:disabled="! sure">Remove Invite</button>
            </form>
        </div>
    </div>
@endsection
