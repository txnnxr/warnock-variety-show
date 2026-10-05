<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InviteSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_invites_cannot_be_reached_by_id(): void
    {
        $invite = Invite::factory()->attending()->create();

        $this->get("/invites/{$invite->id}/edit")->assertNotFound();
        $this->get("/invites/{$invite->id}/thank-you")->assertNotFound();
        $this->get("/invites/{$invite->id}/calendar")->assertNotFound();
        $this->post("/invites/{$invite->id}/generate-ics")->assertNotFound();
    }

    public function test_invites_are_reachable_by_key(): void
    {
        $invite = Invite::factory()->attending()->create();

        $this->get("/invites/{$invite->key}/thank-you")->assertOk()->assertSee($invite->first_name);
        $this->get("/invites/{$invite->key}/calendar")
            ->assertOk()
            ->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
    }

    public function test_calendar_is_only_for_confirmed_guests(): void
    {
        $maybe = Invite::factory()->create(['response_status' => Invite::MAYBE]);
        $requested = Invite::factory()->attending()->create(['guest_request' => true]);

        $this->get("/invites/{$maybe->key}/calendar")->assertNotFound();
        $this->get("/invites/{$requested->key}/calendar")->assertNotFound();
    }

    public function test_respond_requires_the_matching_show(): void
    {
        $invite = Invite::factory()->create();
        $otherShow = Show::factory()->create();

        $this->get("/shows/{$otherShow->id}/invite/respond/{$invite->key}")->assertNotFound();
        $this->get("/shows/{$invite->show_id}/invite/respond/{$invite->key}")->assertOk();
    }

    public function test_mark_as_opened_uses_the_key(): void
    {
        $invite = Invite::factory()->create(['response_status' => 'PENDING - SENT']);

        $this->post("/invites/{$invite->key}/mark-as-opened")->assertNoContent();

        $this->assertSame('PENDING - OPENED', $invite->fresh()->response_status);
    }

    public function test_mark_as_sent_requires_an_admin(): void
    {
        $invite = Invite::factory()->create();

        $this->post("/invites/{$invite->id}/mark-as-sent")->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->post("/invites/{$invite->id}/mark-as-sent")->assertForbidden();
        $this->actingAs(User::factory()->admin()->create())->post("/invites/{$invite->id}/mark-as-sent")->assertRedirect();

        $this->assertSame('PENDING - SENT', $invite->fresh()->response_status);
    }

    public function test_application_details_are_only_public_by_key(): void
    {
        $application = \App\Models\SubmissionApplication::factory()->create();

        $this->get("/shows/{$application->show_id}/submission-applications/{$application->id}/view")->assertRedirect('/login');
        $this->get("/applications/{$application->key}")->assertOk()->assertSee($application->title);
    }
}
