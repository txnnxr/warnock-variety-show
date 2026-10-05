@extends('layouts.app')
@section('title', 'People')
@section('content')
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    @if($duplicateGroups->isNotEmpty())
        <div class="card">
            <div class="card-body">
                <h2 class="section-heading">Possible Duplicates</h2>
                <p>These look like the same person entered more than once. Pick the record to keep, untick anyone who isn't really a match, and merge. Invites, applications and performances move to the kept record, and missing contact details are filled in.</p>

                @foreach($duplicateGroups as $group)
                    <form method="POST" action="{{ route('people.merge-many') }}" class="duplicate-group"
                          x-data="{ keep: {{ $group['keep']->id }}, names: @js($group['people']->pluck('name', 'id')) }"
                          x-on:submit="const count = $el.querySelectorAll('[name=\'merge_ids[]\']:checked:enabled').length;
                                       if (! confirm(`Merge ${count} ${count === 1 ? 'record' : 'records'} into ${names[keep]}? This can't be undone.`)) $event.preventDefault()">
                        @csrf
                        <p class="small text-muted mb-2">{{ ucfirst(implode(' · ', $group['reasons'])) }}</p>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-2">
                                <thead>
                                    <tr>
                                        <th scope="col">Keep</th>
                                        <th scope="col">Merge</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">History</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group['people'] as $person)
                                        <tr>
                                            <td><input class="form-check-input" type="radio" name="keep_id" value="{{ $person->id }}" x-model.number="keep" aria-label="Keep {{ $person->name }}"></td>
                                            <td><input class="form-check-input" type="checkbox" name="merge_ids[]" value="{{ $person->id }}" checked x-bind:disabled="keep === {{ $person->id }}" x-bind:class="{ invisible: keep === {{ $person->id }} }" aria-label="Merge {{ $person->name }}"></td>
                                            <td><a href="{{ route('people.show', $person) }}">{{ $person->name }}</a></td>
                                            <td class="text-break">{{ $person->email }}</td>
                                            <td>{{ $person->phone_number }}</td>
                                            <td class="text-nowrap">{{ $person->invites_count }} {{ Str::plural('invite', $person->invites_count) }}@if($person->submission_applications_count), {{ $person->submission_applications_count }} {{ Str::plural('application', $person->submission_applications_count) }}@endif</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Merge into <span x-text="names[keep]">{{ $group['keep']->name }}</span></button>
                    </form>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card" x-data="{
        selected: [],
        keep: null,
        toggle(id, name, on) {
            this.selected = on ? [...this.selected, { id, name }] : this.selected.filter((person) => person.id !== id);
            if (! this.selected.some((person) => person.id === this.keep)) this.keep = this.selected[0]?.id ?? null;
        },
    }">
        <div class="card-body">
            <h1 class="card-heading">People</h1>
            <p class="text-muted small">Tick two or more people to merge them.</p>

            <form method="POST" action="{{ route('people.merge-many') }}" class="merge-bar" x-show="selected.length > 1" x-cloak
                  x-on:submit="if (! confirm(`Merge ${selected.length - 1} ${selected.length === 2 ? 'person' : 'people'} into ${selected.find((person) => person.id === keep)?.name}? This can't be undone.`)) $event.preventDefault()">
                @csrf
                <span x-text="`${selected.length} selected.`"></span>
                <label for="merge-keep">Keep</label>
                <select id="merge-keep" class="form-select form-select-sm w-auto" name="keep_id" x-model.number="keep">
                    <template x-for="person in selected" :key="person.id">
                        <option x-bind:value="person.id" x-text="person.name" x-bind:selected="person.id === keep"></option>
                    </template>
                </select>
                <template x-for="person in selected.filter((person) => person.id !== keep)" :key="person.id">
                    <input type="hidden" name="merge_ids[]" x-bind:value="person.id">
                </template>
                <button type="submit" class="btn btn-sm btn-primary">Merge</button>
            </form>

            <table class="table dt-responsive w-100" id="peopleTable">
                <thead>
                    <tr>
                        <th class="all"><span class="visually-hidden">Select</span></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Attended</th>
                        <th>Performed</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($people as $person)
                    <tr>
                        <td><input class="form-check-input" type="checkbox" aria-label="Select {{ $person->name }}" x-on:change="toggle({{ $person->id }}, @js($person->name), $event.target.checked)"></td>
                        <td><a href="{{ route('people.show', $person) }}">{{ $person->name }}</a></td>
                        <td class="text-break">{{ $person->email }}</td>
                        <td>{{ $person->phone_number }}</td>
                        <td>{{ $person->attended_count }}</td>
                        <td>{{ $person->performed_count }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#peopleTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[1, 'asc']],
                columnDefs: [{ targets: 0, orderable: false, searchable: false, width: '1.5rem' }],
            });
        });
    </script>
@endpush
