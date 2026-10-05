<?php

namespace Tests\Feature;

use App\Mail\GuestRequestApproved;
use App\Mail\WaitlistPromoted;
use App\Models\Invite;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RsvpTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_rsvp_and_see_the_address(): void
    {
        $invite = Invite::factory()->create(['response_status' => 'PENDING - SENT']);

        $this->post("/shows/{$invite->show_id}/invite/respond/{$invite->key}", [
            'response_status' => 'ATTENDING',
            'talent' => 1,
        ])->assertRedirect("/invites/{$invite->key}/thank-you");

        $this->assertSame('ATTENDING', $invite->fresh()->response_status);
        $this->get("/invites/{$invite->key}/thank-you")->assertSee($invite->show->address);
    }

    public function test_the_address_is_hidden_from_maybes_and_the_public(): void
    {
        $invite = Invite::factory()->create(['response_status' => Invite::MAYBE]);

        $this->get("/invites/{$invite->key}/thank-you")->assertDontSee($invite->show->address);
        $this->get("/shows/{$invite->show_id}/view")->assertDontSee($invite->show->address);
    }

    public function test_invalid_responses_are_rejected(): void
    {
        $invite = Invite::factory()->create();

        $this->post("/shows/{$invite->show_id}/invite/respond/{$invite->key}", ['response_status' => 'HACKED'])
            ->assertSessionHasErrors('response_status');
    }

    public function test_guests_go_on_the_waitlist_when_the_show_is_full(): void
    {
        $show = Show::factory()->create(['max_attendants' => 3]);
        Invite::factory()->attending(plusOne: true)->for($show)->create();
        Invite::factory()->attending()->for($show)->create();
        $invite = Invite::factory()->for($show)->create();

        $this->post("/shows/{$show->id}/invite/respond/{$invite->key}", ['response_status' => 'ATTENDING']);

        $this->assertSame(Invite::WAITLIST, $invite->fresh()->response_status);
        $this->assertNotNull($invite->fresh()->waitlisted_at);
        $this->get("/invites/{$invite->key}/thank-you")
            ->assertSee('waitlist')
            ->assertDontSee($show->address);
    }

    public function test_plus_ones_take_a_seat(): void
    {
        $show = Show::factory()->create(['max_attendants' => 2]);
        Invite::factory()->attending()->for($show)->create();
        $invite = Invite::factory()->for($show)->create(['has_plus_one_option' => true]);

        $this->post("/shows/{$show->id}/invite/respond/{$invite->key}", [
            'response_status' => 'ATTENDING',
            'plus_one_status' => 1,
        ]);

        $this->assertSame(Invite::WAITLIST, $invite->fresh()->response_status);
    }

    public function test_plus_ones_are_ignored_when_the_invite_does_not_allow_them(): void
    {
        $invite = Invite::factory()->create(['has_plus_one_option' => false]);

        $this->post("/shows/{$invite->show_id}/invite/respond/{$invite->key}", [
            'response_status' => 'ATTENDING',
            'plus_one_status' => 1,
        ]);

        $this->assertFalse($invite->fresh()->plus_one_status);
    }

    public function test_giving_up_a_seat_promotes_the_waitlist_in_order(): void
    {
        Mail::fake();
        $show = Show::factory()->create(['max_attendants' => 1]);
        $leaving = Invite::factory()->attending()->for($show)->create();
        $first = Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST, 'waitlisted_at' => now()->subHour()]);
        $second = Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST, 'waitlisted_at' => now()]);

        $this->post("/shows/{$show->id}/invite/respond/{$leaving->key}", ['response_status' => 'NO']);

        $this->assertSame(Invite::ATTENDING, $first->fresh()->response_status);
        $this->assertSame(Invite::WAITLIST, $second->fresh()->response_status);
        Mail::assertQueued(WaitlistPromoted::class, fn ($mail) => $mail->hasTo($first->email));
        Mail::assertNotQueued(WaitlistPromoted::class, fn ($mail) => $mail->hasTo($second->email));
    }

    public function test_raising_capacity_promotes_the_waitlist(): void
    {
        Mail::fake();
        $show = Show::factory()->create(['max_attendants' => 1]);
        Invite::factory()->attending()->for($show)->create();
        $waiting = Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST, 'waitlisted_at' => now()]);

        $this->actingAs(User::factory()->admin()->create())->put("/shows/{$show->id}", [
            'name' => $show->name,
            'date' => $show->date,
            'max_attendants' => 2,
            'address' => $show->address,
        ]);

        $this->assertSame(Invite::ATTENDING, $waiting->fresh()->response_status);
    }

    public function test_guests_can_change_their_response(): void
    {
        $invite = Invite::factory()->attending()->create();

        $this->get("/shows/{$invite->show_id}/invite/respond/{$invite->key}")->assertSee('Thank you');
        $this->get("/shows/{$invite->show_id}/invite/respond/{$invite->key}?change=1")->assertSee('RSVP');
        $this->assertSame('ATTENDING', $invite->fresh()->response_status);
    }

    public function test_guest_requests_are_saved_linked_to_a_person_and_pending_approval(): void
    {
        $show = Show::factory()->create();

        $response = $this->post("/shows/{$show->id}/invite/guest-request", [
            'first_name' => 'Sam',
            'last_name' => 'Rivera',
            'email' => 'sam@example.com',
            'response_status' => 'ATTENDING',
            'plus_one_status' => 0,
        ]);

        $invite = Invite::where('email', 'sam@example.com')->firstOrFail();
        $response->assertRedirect("/invites/{$invite->key}/thank-you");
        $this->assertTrue($invite->guest_request);
        $this->assertSame('ATTENDING', $invite->response_status);
        $this->assertSame('Sam Rivera', $invite->person->name);
        $this->get("/invites/{$invite->key}/thank-you")
            ->assertSee('waiting for approval')
            ->assertDontSee($show->address);
    }

    public function test_guest_requests_require_a_first_name(): void
    {
        $show = Show::factory()->create();

        $this->post("/shows/{$show->id}/invite/guest-request", ['response_status' => 'ATTENDING'])
            ->assertSessionHasErrors('first_name');
    }

    public function test_approving_a_guest_request_emails_them(): void
    {
        Mail::fake();
        $invite = Invite::factory()->attending()->create(['guest_request' => true]);

        $this->actingAs(User::factory()->admin()->create())
            ->post("/invites/{$invite->id}/guest-request/approve");

        $this->assertFalse($invite->fresh()->guest_request);
        Mail::assertQueued(GuestRequestApproved::class, fn ($mail) => $mail->hasTo($invite->email));
    }
}
