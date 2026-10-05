<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{__("Edit " . $show->title)}}
        </h2>
    </x-slot>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ __($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('shows.update', $show->id) }}">
        @csrf
        @method('PUT') <!-- or @method('PATCH') -->
        <div>
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $show->title) }}" required>
        </div>
        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required>{{ old('description', $show->description) }}</textarea>
        </div>
        <div>
            <label for="date_time">Date & Time</label>
            <input type="datetime-local" id="date_time" name="date_time" value="{{ old('date_time', $show->date_time->format('Y-m-d\TH:i')) }}" required>
        </div>
        <div>
            <label for="max_guests">Max Guests</label>
            <input type="number" id="max_guests" name="max_guests" value="{{ old('max_guests', $show->max_guests) }}" required>
        </div>
        <button type="submit">Update</button>
        <a href="{{ route('shows.show', ['show' => $show]) }}">Cancel</a>
    </form>

</x-app-layout>
