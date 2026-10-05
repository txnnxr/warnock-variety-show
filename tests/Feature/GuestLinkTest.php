<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestLinkTest extends TestCase
{
    use RefreshDatabase;

    private function rsvp(Show $show, array $extra = [])
    {
        return $this->post("/shows/{$show->id}/invite/guest-request", array_merge([
            'first_name' => 'Sam',
            'response_status' => 'ATTENDING',
            'plus_one_status' => 0,
        ], $extra));
    }

    public function test_every_show_gets_a_secret_guest_link(): void
    {
        $show = Show::factory()->create();

        $this->assertNotEmpty($show->guest_link_key);
        $this->get("/join/{$show->guest_link_key}")->assertOk()->assertSee($show->guest_link_key);
        $this->get('/join/not-a-real-key')->assertNotFound();
    }

    public function test_rsvps_through_the_guest_link_are_approved_and_see_the_address(): void
    {
        $show = Show::factory()->create();

        $this->rsvp($show, ['guest_link_key' => $show->guest_link_key]);

        $invite = Invite::firstOrFail();
        $this->assertFalse($invite->guest_request);
        $this->assertSame(Invite::ATTENDING, $invite->response_status);
        $this->get("/invites/{$invite->key}/thank-you")->assertSee($show->address);
    }

    public function test_rsvps_from_the_public_show_page_still_need_approval(): void
    {
        $show = Show::factory()->create();

        $this->rsvp($show);

        $invite = Invite::firstOrFail();
        $this->assertTrue($invite->guest_request);
        $this->get("/invites/{$invite->key}/thank-you")->assertDontSee($show->address);
    }

    public function test_a_wrong_or_other_shows_key_does_not_approve(): void
    {
        $show = Show::factory()->create();
        $other = Show::factory()->create();

        $this->rsvp($show, ['guest_link_key' => 'guessed']);
        $this->rsvp($show, ['guest_link_key' => $other->guest_link_key]);

        $this->assertSame(2, Invite::where('guest_request', true)->count());
    }

    public function test_guest_link_rsvps_respect_capacity(): void
    {
        $show = Show::factory()->create(['max_attendants' => 1]);
        Invite::factory()->attending()->for($show)->create();

        $this->rsvp($show, ['guest_link_key' => $show->guest_link_key]);

        $invite = Invite::latest('id')->firstOrFail();
        $this->assertSame(Invite::WAITLIST, $invite->response_status);
        $this->assertFalse($invite->guest_request);
    }

    public function test_admin_can_reset_the_guest_link(): void
    {
        $show = Show::factory()->create();
        $oldKey = $show->guest_link_key;

        $this->actingAs(User::factory()->admin()->create())
            ->post("/shows/{$show->id}/invite/reset-guest-link")
            ->assertRedirect();

        $this->assertNotSame($oldKey, $show->fresh()->guest_link_key);
        $this->get("/join/{$oldKey}")->assertNotFound();

        $this->rsvp($show, ['guest_link_key' => $oldKey]);
        $this->assertTrue(Invite::firstOrFail()->guest_request);
    }

    public function test_only_admins_can_reset_the_guest_link(): void
    {
        $show = Show::factory()->create();

        $this->post("/shows/{$show->id}/invite/reset-guest-link")->assertRedirect('/login');
        $this->actingAs(User::factory()->create())
            ->post("/shows/{$show->id}/invite/reset-guest-link")
            ->assertForbidden();
    }

    public function test_invites_page_shares_the_secret_link(): void
    {
        $show = Show::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get("/shows/{$show->id}/invite")
            ->assertSee("/join/{$show->guest_link_key}");
    }
}
