@extends('shows.layout')
@push('meta')
    <meta property="og:title" content="{{$show->name}} - {{$invite->first_name}} Invitation" />
@endpush
@section('shows-content')
    @if($show->canceled)
        <div class="alert alert-warning">This show has been canceled, so RSVPs are closed.</div>
    @else
    <form action="/shows/{{$show->id}}/invite/respond/{{$invite->key}}" method="POST" class="card">
        @csrf
        <div class="card-body">
            <h2 class="section-heading">{{$invite->first_name}} {{$invite->middle_name}} {{$invite->last_name}}, you're invited</h2>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <fieldset class="choice-group h-100">
                        <legend>Attending?</legend>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="response_status" id="attending-yes" value="ATTENDING" @checked(! in_array($invite->response_status, ['NO', 'COWARD']))>
                            <label class="form-check-label" for="attending-yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="response_status" id="attending-no" value="NO" @checked($invite->response_status == 'NO')>
                            <label class="form-check-label" for="attending-no">No</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="response_status" id="attending-maybe" value="COWARD" @checked($invite->response_status == 'COWARD')>
                            <label class="form-check-label" for="attending-maybe">Maybe</label>
                        </div>
                    </fieldset>
                </div>
                <div class="col-12 col-md-4 talent-box">
                    <fieldset class="choice-group h-100">
                        <legend>Talent?</legend>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="talent" id="talent-yes" value="1" @checked($invite->talent || $invite->response_status == 'CREATED' || str_starts_with((string) $invite->response_status, 'PENDING'))>
                            <label class="form-check-label" for="talent-yes">Ye</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="talent" id="talent-no" value="0" @checked(! ($invite->talent || $invite->response_status == 'CREATED' || str_starts_with((string) $invite->response_status, 'PENDING')))>
                            <label class="form-check-label" for="talent-no">Nay</label>
                        </div>
                    </fieldset>
                </div>
                @if($invite->has_plus_one_option)
                    <div class="col-12 col-md-4 plus-one-box">
                        <fieldset class="choice-group h-100">
                            <legend>Bringing a plus one?</legend>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="plus_one_status" id="plus-one-yes" value="1" @checked($invite->plus_one_status)>
                                <label class="form-check-label" for="plus-one-yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="plus_one_status" id="plus-one-no" value="0" @checked(! $invite->plus_one_status)>
                                <label class="form-check-label" for="plus-one-no">No</label>
                            </div>
                        </fieldset>
                    </div>
                @endif
            </div>
            <div class="d-grid d-sm-flex justify-content-sm-end mt-4">
                <button class="btn btn-primary" type="submit">Send RSVP</button>
            </div>
        </div>
    </form>
    @endif
@endsection
@push('scripts')
<script>
    @if($invite->response_status == 'PENDING - SENT')
        $(document).ready(function(){
            $.post( "{{ route('invites.mark-as-opened', $invite) }}", {"_token": "{{ csrf_token() }}"});
        });
    @endif
    function toggleExtras() {
        var attending = $('[name=response_status]:checked').val() !== 'NO';
        $('[name=talent], [name=plus_one_status]').prop('disabled', ! attending);
        $('.talent-box, .plus-one-box').toggle(attending);
    }
    $('[name=response_status]').change(toggleExtras);
    toggleExtras();
</script>
@endpush
