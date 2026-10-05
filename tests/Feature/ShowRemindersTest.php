<?php

namespace Tests\Feature;

use App\Mail\MaybeNudge;
use App\Mail\ShowReminder;
use App\Models\Invite;
use App\Models\Show;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ShowRemindersTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirmed_guests_get_one_reminder_the_day_before(): void
    {
        Mail::fake();
        $show = Show::factory()->create(['date' => now()->addHours(20)]);
        $attending = Invite::factory()->attending()->for($show)->create();
        Invite::factory()->attending()->for($show)->create(['guest_request' => true]);
        Invite::factory()->for($show)->create(['response_status' => Invite::NO]);

        $this->artisan('app:send-reminders')->assertSuccessful();
        $this->artisan('app:send-reminders')->assertSuccessful();

        Mail::assertQueued(ShowReminder::class, 1);
        Mail::assertQueued(ShowReminder::class, fn ($mail) => $mail->hasTo($attending->email));
        $this->assertNotNull($attending->fresh()->reminder_sent_at);
    }

    public function test_reminders_wait_until_the_day_before(): void
    {
        Mail::fake();
        $show = Show::factory()->create(['date' => now()->addDays(2)->addHours(6)]);
        Invite::factory()->attending()->for($show)->create();

        $this->artisan('app:send-reminders');

        Mail::assertNotQueued(ShowReminder::class);
    }

    public function test_maybes_get_one_nudge_three_days_out(): void
    {
        Mail::fake();
        $soon = Show::factory()->create(['date' => now()->addDays(2)]);
        $later = Show::factory()->create(['date' => now()->addDays(10)]);
        $maybe = Invite::factory()->for($soon)->create(['response_status' => Invite::MAYBE]);
        Invite::factory()->for($later)->create(['response_status' => Invite::MAYBE]);

        $this->artisan('app:send-reminders');
        $this->artisan('app:send-reminders');

        Mail::assertQueued(MaybeNudge::class, 1);
        Mail::assertQueued(MaybeNudge::class, fn ($mail) => $mail->hasTo($maybe->email));
    }

    public function test_canceled_and_past_shows_are_skipped(): void
    {
        Mail::fake();
        $canceled = Show::factory()->create(['date' => now()->addHours(10), 'canceled' => true]);
        $past = Show::factory()->create(['date' => now()->subDay()]);
        Invite::factory()->attending()->for($canceled)->create();
        Invite::factory()->attending()->for($past)->create();

        $this->artisan('app:send-reminders');

        Mail::assertNothingOutgoing();
    }

    public function test_the_reminder_includes_the_address(): void
    {
        $invite = Invite::factory()->attending()->create();

        (new ShowReminder($invite))->assertSeeInHtml($invite->show->address);
    }
}
