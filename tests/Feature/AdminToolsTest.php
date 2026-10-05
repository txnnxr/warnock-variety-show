<?php

namespace Tests\Feature;

use App\Mail\ActApproved;
use App\Mail\ActDeclined;
use App\Mail\ShowCanceled;
use App\Mail\WaitlistPromoted;
use App\Models\Exhibitor;
use App\Models\Invite;
use App\Models\Person;
use App\Models\Show;
use App\Models\SubmissionApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminToolsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    // Performer emails

    public function test_approving_emails_the_performer_their_slot_and_the_address(): void
    {
        Mail::fake();
        $show = Show::factory()->create();
        SubmissionApplication::factory()->for($show)->create()->approve();
        $application = SubmissionApplication::factory()->for($show)->create(['name' => 'Pat Juggler', 'title' => 'Fire Juggling']);

        $this->actingAs($this->admin())->post("/submission-applications/{$application->id}/approve");

        Mail::assertQueued(ActApproved::class, fn ($mail) => $mail->hasTo($application->email));
        (new ActApproved($application->fresh()))
            ->assertSeeInText('2 of 2')
            ->assertSeeInHtml($show->address)
            ->assertSeeInHtml('Hi Pat');
    }

    public function test_reapproving_does_not_email_twice(): void
    {
        Mail::fake();
        $application = SubmissionApplication::factory()->create(['approved' => true]);

        $this->actingAs($this->admin())->post("/submission-applications/{$application->id}/approve");

        Mail::assertNotQueued(ActApproved::class);
    }

    public function test_declining_only_emails_when_asked(): void
    {
        Mail::fake();
        $quiet = SubmissionApplication::factory()->create();
        $told = SubmissionApplication::factory()->create();

        $this->actingAs($this->admin())->post("/submission-applications/{$quiet->id}/deny");
        $this->actingAs($this->admin())->post("/submission-applications/{$told->id}/deny", ['notify' => 1]);

        Mail::assertQueued(ActDeclined::class, 1);
        Mail::assertQueued(ActDeclined::class, fn ($mail) => $mail->hasTo($told->email));
        (new ActDeclined($told))->assertDontSeeInHtml($told->show->address);
    }

    // Editing and removing invites

    public function test_admin_can_edit_an_invite(): void
    {
        $invite = Invite::factory()->create(['first_name' => 'Jhon', 'email' => 'jhon@example.com']);

        $this->actingAs($this->admin())->get("/invites/{$invite->id}/edit")->assertOk()->assertSee('Jhon');
        $this->actingAs($this->admin())->put("/invites/{$invite->id}", [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@example.com',
            'response_status' => 'ATTENDING',
            'talent' => 1,
        ])->assertRedirect("/shows/{$invite->show_id}/invite");

        $invite->refresh();
        $this->assertSame('John', $invite->first_name);
        $this->assertSame('ATTENDING', $invite->response_status);
        $this->assertTrue($invite->talent);
        $this->assertSame('john@example.com', $invite->person->email);
    }

    public function test_freeing_a_seat_by_editing_promotes_the_waitlist(): void
    {
        Mail::fake();
        $show = Show::factory()->create(['max_attendants' => 2]);
        $couple = Invite::factory()->attending(plusOne: true)->for($show)->create(['has_plus_one_option' => true]);
        $waiting = Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST, 'waitlisted_at' => now()]);

        $this->actingAs($this->admin())->put("/invites/{$couple->id}", [
            'first_name' => $couple->first_name,
            'response_status' => 'ATTENDING',
            'has_plus_one_option' => 1,
        ]);

        $this->assertFalse($couple->fresh()->plus_one_status);
        $this->assertSame(Invite::ATTENDING, $waiting->fresh()->response_status);
        Mail::assertQueued(WaitlistPromoted::class);
    }

    public function test_removing_an_invite_soft_deletes_it_and_promotes_the_waitlist(): void
    {
        Mail::fake();
        $show = Show::factory()->create(['max_attendants' => 1]);
        $leaving = Invite::factory()->attending()->for($show)->create();
        $waiting = Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST, 'waitlisted_at' => now()]);

        $this->actingAs($this->admin())->delete("/invites/{$leaving->id}")->assertRedirect("/shows/{$show->id}/invite");

        $this->assertSoftDeleted($leaving);
        $this->assertSame(Invite::ATTENDING, $waiting->fresh()->response_status);
        $this->get("/invites/{$leaving->key}/thank-you")->assertNotFound();
    }

    public function test_only_admins_can_edit_or_remove_invites(): void
    {
        $invite = Invite::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->put("/invites/{$invite->id}", ['first_name' => 'Hacked', 'response_status' => 'ATTENDING'])->assertForbidden();
        $this->actingAs($user)->delete("/invites/{$invite->id}")->assertForbidden();
        $this->assertNotSoftDeleted($invite);
    }

    // Merging people

    public function test_merging_moves_everything_to_the_kept_person(): void
    {
        $keep = Person::create(['name' => 'Sam Rivera', 'email' => 'sam@example.com']);
        $duplicate = Person::create(['name' => 'Sam', 'phone_number' => '5551234567']);
        $invite = Invite::factory()->create(['person_id' => $duplicate->id]);
        $application = SubmissionApplication::factory()->create(['person_id' => $duplicate->id]);
        $application->approve();

        $this->actingAs($this->admin())
            ->post("/people/{$keep->id}/merge", ['duplicate_id' => $duplicate->id])
            ->assertRedirect("/people/{$keep->id}");

        $this->assertSame($keep->id, $invite->fresh()->person_id);
        $this->assertSame($keep->id, $application->fresh()->person_id);
        $this->assertSame($keep->id, Exhibitor::first()->person_id);
        $this->assertSame('5551234567', $keep->fresh()->phone_number);
        $this->assertSame('sam@example.com', $keep->fresh()->email);
        $this->assertSoftDeleted($duplicate);
    }

    public function test_a_person_cannot_be_merged_into_themselves(): void
    {
        $person = Person::create(['name' => 'Sam Rivera']);

        $this->actingAs($this->admin())
            ->post("/people/{$person->id}/merge", ['duplicate_id' => $person->id])
            ->assertSessionHasErrors('duplicate_id');

        $this->assertNotSoftDeleted($person);
    }

    // Canceling shows

    public function test_canceling_emails_interested_guests_with_the_note(): void
    {
        Mail::fake();
        $show = Show::factory()->create();
        $attending = Invite::factory()->attending()->for($show)->create();
        $maybe = Invite::factory()->for($show)->create(['response_status' => Invite::MAYBE]);
        $no = Invite::factory()->for($show)->create(['response_status' => Invite::NO]);

        $this->actingAs($this->admin())
            ->post("/shows/{$show->id}/cancel", ['notify' => 1, 'note' => 'The host has the flu.'])
            ->assertSessionHas('status', 'Show canceled. Emailed 2 guests.');

        $this->assertTrue($show->fresh()->canceled);
        Mail::assertQueued(ShowCanceled::class, 2);
        Mail::assertNotQueued(ShowCanceled::class, fn ($mail) => $mail->hasTo($no->email));
        (new ShowCanceled($attending, 'The host has the flu.'))->assertSeeInHtml('The host has the flu.');
    }

    public function test_canceling_without_notify_sends_nothing(): void
    {
        Mail::fake();
        $show = Show::factory()->create();
        Invite::factory()->attending()->for($show)->create();

        $this->actingAs($this->admin())->post("/shows/{$show->id}/cancel");

        $this->assertTrue($show->fresh()->canceled);
        Mail::assertNothingOutgoing();
    }

    public function test_canceled_shows_are_hidden_and_closed(): void
    {
        $show = Show::factory()->create(['name' => 'Rained Out', 'canceled' => true]);
        $invite = Invite::factory()->for($show)->create(['response_status' => 'PENDING - SENT']);

        $this->get('/')->assertDontSee('Rained Out');
        $this->get('/card')->assertRedirect('/');
        $this->get("/shows/{$show->id}/view")->assertSee('Canceled')->assertDontSee('data-bs-target="#rsvpModal"', false);
        $this->get("/shows/{$show->id}/invite/respond/{$invite->key}?change=1")->assertSee('RSVPs are closed');
        $this->get("/shows/{$show->id}/submission-applications/create")->assertRedirect("/shows/{$show->id}/view");

        $this->post("/shows/{$show->id}/invite/respond/{$invite->key}", ['response_status' => 'ATTENDING']);
        $this->post("/shows/{$show->id}/invite/guest-request", ['first_name' => 'Sneaky', 'response_status' => 'ATTENDING']);
        $this->post("/shows/{$show->id}/submission-applications", ['name' => 'Sneaky', 'title' => 'Act']);

        $this->assertSame('PENDING - SENT', $invite->fresh()->response_status);
        $this->assertSame(1, Invite::count());
        $this->assertSame(0, SubmissionApplication::count());
    }

    public function test_restoring_reopens_the_show(): void
    {
        $show = Show::factory()->create(['canceled' => true]);

        $this->actingAs($this->admin())->post("/shows/{$show->id}/restore");

        $this->assertFalse($show->fresh()->canceled);
        $this->get('/')->assertSee($show->name);
    }

    public function test_only_admins_can_cancel(): void
    {
        $show = Show::factory()->create();

        $this->actingAs(User::factory()->create())->post("/shows/{$show->id}/cancel")->assertForbidden();
        $this->assertFalse($show->fresh()->canceled);
    }
}
