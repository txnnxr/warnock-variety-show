<?php

namespace Tests\Feature;

use App\Mail\GuestRequestApproved;
use App\Models\Invite;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WorthFixingTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_guest_requests_do_not_take_seats(): void
    {
        $show = Show::factory()->create(['max_attendants' => 1]);
        Invite::factory()->attending()->for($show)->count(5)->create(['guest_request' => true]);
        $invite = Invite::factory()->for($show)->create();

        $this->post("/shows/{$show->id}/invite/respond/{$invite->key}", ['response_status' => 'ATTENDING']);

        $this->assertSame(Invite::ATTENDING, $invite->fresh()->response_status);
        $this->assertSame(1, $show->seatsTaken());
    }

    public function test_pending_guest_requests_are_not_listed_as_attending(): void
    {
        $show = Show::factory()->create();
        Invite::factory()->attending()->for($show)->create(['first_name' => 'Unapproved', 'guest_request' => true]);

        $this->get("/shows/{$show->id}/view")->assertDontSee('Unapproved');
    }

    public function test_approving_a_request_for_a_full_show_waitlists_it_and_says_so(): void
    {
        Mail::fake();
        $show = Show::factory()->create(['max_attendants' => 1]);
        Invite::factory()->attending()->for($show)->create();
        $request = Invite::factory()->attending()->for($show)->create(['guest_request' => true]);

        $this->actingAs(User::factory()->admin()->create())
            ->post("/invites/{$request->id}/guest-request/approve");

        $this->assertSame(Invite::WAITLIST, $request->fresh()->response_status);
        $this->assertFalse($request->fresh()->guest_request);
        Mail::assertQueued(GuestRequestApproved::class, fn ($mail) => $mail->hasTo($request->email));
    }

    public function test_the_waitlist_skips_unapproved_requests(): void
    {
        Mail::fake();
        $show = Show::factory()->create(['max_attendants' => 1]);
        $leaving = Invite::factory()->attending()->for($show)->create();
        $pending = Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST, 'waitlisted_at' => now()->subDay(), 'guest_request' => true]);
        $approved = Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST, 'waitlisted_at' => now()]);

        $leaving->respond(Invite::NO);

        $this->assertSame(Invite::WAITLIST, $pending->fresh()->response_status);
        $this->assertSame(Invite::ATTENDING, $approved->fresh()->response_status);
    }

    public function test_public_forms_are_rate_limited(): void
    {
        $show = Show::factory()->create();

        foreach (range(1, 5) as $i) {
            $this->post("/shows/{$show->id}/invite/guest-request", ['first_name' => "Guest {$i}", 'response_status' => 'ATTENDING'])
                ->assertRedirect();
        }

        $this->post("/shows/{$show->id}/invite/guest-request", ['first_name' => 'Spammer', 'response_status' => 'ATTENDING'])
            ->assertTooManyRequests();
        $this->post("/shows/{$show->id}/submission-applications", ['name' => 'Spammer', 'title' => 'Spam'])
            ->assertTooManyRequests();
        $this->assertSame(5, Invite::count());
    }

    public function test_descriptions_are_stored_as_typed_and_escaped_on_display(): void
    {
        $show = Show::factory()->create();
        $description = "Tom & Jerry's <script>alert(1)</script> night";

        $this->actingAs(User::factory()->admin()->create())->put("/shows/{$show->id}", [
            'name' => $show->name,
            'description' => $description,
            'date' => $show->date,
            'max_attendants' => 30,
            'address' => $show->address,
        ]);

        $this->assertSame($description, $show->fresh()->description);
        $this->get("/shows/{$show->id}/view")
            ->assertSee("Tom &amp; Jerry&#039;s &lt;script&gt;", false)
            ->assertDontSee('<script>alert(1)</script>', false);
        $this->get("/shows/{$show->id}/edit")->assertSee(e($description), false);
    }

    public function test_calendar_file_has_no_html_entities(): void
    {
        $show = Show::factory()->create(['description' => "Tom & Jerry's night"]);
        $invite = Invite::factory()->attending()->for($show)->create();

        $this->assertStringContainsString("Tom & Jerry's night", $invite->toICS());
    }

    public function test_unescape_migration_decodes_old_descriptions(): void
    {
        $show = Show::factory()->create();
        DB::table('shows')->where('id', $show->id)->update(['description' => 'Tom &amp; Jerry&#039;s night']);

        (require database_path('migrations/2026_10_05_000001_unescape_show_descriptions.php'))->up();

        $this->assertSame("Tom & Jerry's night", $show->fresh()->description);
    }

    public function test_invite_keys_are_unique(): void
    {
        $invite = Invite::factory()->create();

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);

        Invite::factory()->create(['key' => $invite->key]);
    }
}
