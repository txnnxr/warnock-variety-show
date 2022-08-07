<div class="card mt-3">
    <div class="card-body p-4">
        <h5 class="card-title text-center">Generate Invite</h5>
{{--        action="/shows/{{$show->id}}/invite" method="POST"--}}
        <form class="row"  wire:submit.prevent="store">
            @csrf
            <div class="col-md-6 my-1">
                <input class="form-control" type="text" name="first_name" placeholder="First Name (required)" wire:keydown="validate">
            </div>
            <div class="col-md-6 my-1">
                <input class="form-control" type="text" name="last_name" placeholder="Last Name (optional)">
            </div>
            <div class="col-md-6 my-1">
                <label for="has_plus_one_option">Give invitation optional plus one?</label>
                <input class="" type="checkbox" name="has_plus_one_option" value="1">
            </div>
            <div class="col-md-12 my-1">
                <button class="form-control btn btn-primary" type="submit">Create Invite</button>
            </div>
        </form>
    </div>
</div>
