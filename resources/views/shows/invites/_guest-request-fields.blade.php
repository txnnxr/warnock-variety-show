<div class="row g-3">
    <div class="col-12 col-sm-6">
        <label class="form-label" for="rsvp-first-name">First name</label>
        <input class="form-control" type="text" id="rsvp-first-name" name="first_name" required>
    </div>
    <div class="col-12 col-sm-6">
        <label class="form-label" for="rsvp-last-name">Last name <span class="text-muted">(optional)</span></label>
        <input class="form-control" type="text" id="rsvp-last-name" name="last_name">
    </div>
    <div class="col-12 col-sm-6">
        <label class="form-label" for="rsvp-email">Email <span class="text-muted">(optional)</span></label>
        <input class="form-control" type="email" id="rsvp-email" name="email">
    </div>
    <div class="col-12 col-sm-6">
        <label class="form-label" for="rsvp-phone">Phone <span class="text-muted">(optional)</span></label>
        <input class="form-control" type="tel" id="rsvp-phone" name="phone">
    </div>
    <div class="col-12 small text-muted">Leave an email to hear when you're approved, or if you come off the waitlist.</div>
    <div class="col-12 col-sm-6">
        <fieldset class="choice-group h-100">
            <legend>Attending?</legend>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="response_status" id="yes" value="ATTENDING" checked>
                <label class="form-check-label" for="yes">Yes</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="response_status" id="maybe" value="COWARD">
                <label class="form-check-label" for="maybe">Maybe</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="response_status" id="no" value="NO">
                <label class="form-check-label" for="no">No</label>
            </div>
        </fieldset>
    </div>
    <div class="col-12 col-sm-6">
        <fieldset class="choice-group h-100">
            <legend>Bringing a plus one?</legend>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="plus_one_status" id="plus-one-yes" value="1" checked>
                <label class="form-check-label" for="plus-one-yes">Yes</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="plus_one_status" id="plus-one-no" value="0">
                <label class="form-check-label" for="plus-one-no">No</label>
            </div>
        </fieldset>
    </div>
</div>
