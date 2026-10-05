<?php

namespace Tests\Feature;

use App\Mail\LineupAnnouncement;
use App\Mail\ShowRecap;
use App\Models\Invite;
use App\Models\Show;
use App\Models\SubmissionApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShowEmailsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function showWithLineup(array $attributes = []): Show
    {
        $show = Show::factory()->create($attributes);
        SubmissionApplication::factory()->for($show)->create(['name' => 'Pat Juggler', 'title' => 'Fire Juggling'])->approve();

        return $show;
    }

    public function test_lineup_goes_to_everyone_invited_who_has_not_said_no(): void
    {
        Mail::fake();
        $show = $this->showWithLineup();
        $attending = Invite::factory()->attending()->for($show)->create();
        $maybe = Invite::factory()->for($show)->create(['response_status' => Invite::MAYBE]);
        $pending = Invite::factory()->for($show)->create(['response_status' => 'PENDING - SENT']);
        $waitlist = Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST]);
        $no = Invite::factory()->for($show)->create(['response_status' => Invite::NO]);
        $unsent = Invite::factory()->for($show)->create(['response_status' => 'CREATED']);
        Invite::factory()->attending()->for($show)->create(['email' => null]);

        $this->actingAs($this->admin())
            ->post("/shows/{$show->id}/lineup/announce")
            ->assertSessionHas('status', 'Lineup sent to 4 guests.');

        Mail::assertQueued(LineupAnnouncement::class, 4);
        foreach ([$attending, $maybe, $pending, $waitlist] as $invite) {
            Mail::assertQueued(LineupAnnouncement::class, fn ($mail) => $mail->hasTo($invite->email));
        }
        Mail::assertNotQueued(LineupAnnouncement::class, fn ($mail) => $mail->hasTo($no->email) || $mail->hasTo($unsent->email));
        $this->assertNotNull($show->fresh()->lineup_announced_at);
    }

    public function test_lineup_email_lists_the_acts_and_never_the_address(): void
    {
        $show = $this->showWithLineup();
        $maybe = Invite::factory()->for($show)->create(['response_status' => Invite::MAYBE]);

        (new LineupAnnouncement($maybe))
            ->assertSeeInHtml('Fire Juggling')
            ->assertSeeInHtml('Pat Juggler')
            ->assertSeeInHtml('tip the scales')
            ->assertDontSeeInHtml($show->address);
    }

    public function test_lineup_needs_an_upcoming_show_with_acts(): void
    {
        Mail::fake();
        $empty = Show::factory()->create();
        $past = $this->showWithLineup(['date' => now()->subDay()]);
        Invite::factory()->attending()->for($empty)->create();
        Invite::factory()->attending()->for($past)->create();

        $this->actingAs($this->admin())->post("/shows/{$empty->id}/lineup/announce")->assertSessionHasErrors('announce');
        $this->actingAs($this->admin())->post("/shows/{$past->id}/lineup/announce")->assertSessionHasErrors('announce');

        Mail::assertNothingOutgoing();
    }

    public function test_recap_goes_to_confirmed_guests_after_the_show(): void
    {
        Mail::fake();
        $show = $this->showWithLineup(['date' => now()->subDay()]);
        $attended = Invite::factory()->attending()->for($show)->create();
        Invite::factory()->attending()->for($show)->create(['guest_request' => true]);
        Invite::factory()->for($show)->create(['response_status' => Invite::MAYBE]);
        Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST]);

        $this->actingAs($this->admin())
            ->post("/shows/{$show->id}/recap")
            ->assertSessionHas('status', 'Recap sent to 1 guest.');

        Mail::assertQueued(ShowRecap::class, 1);
        Mail::assertQueued(ShowRecap::class, fn ($mail) => $mail->hasTo($attended->email));
        $this->assertNotNull($show->fresh()->recap_sent_at);
    }

    public function test_recap_waits_until_after_the_show(): void
    {
        Mail::fake();
        $show = $this->showWithLineup();
        Invite::factory()->attending()->for($show)->create();

        $this->actingAs($this->admin())->post("/shows/{$show->id}/recap")->assertSessionHasErrors('recap');

        Mail::assertNothingOutgoing();
    }

    public function test_recap_credits_the_lineup_shows_photos_and_plugs_the_next_show(): void
    {
        Storage::fake('public');
        $show = $this->showWithLineup(['date' => now()->subDay()]);
        $photo = $show->photos()->create(['path' => UploadedFile::fake()->image('stage.jpg')->store("shows/{$show->id}", 'public')]);
        $next = Show::factory()->create(['name' => 'The Winter Wonderland', 'date' => now()->addMonth()]);
        $invite = Invite::factory()->attending()->for($show)->create();

        (new ShowRecap($invite))
            ->assertSeeInHtml('Fire Juggling')
            ->assertSeeInHtml($photo->url)
            ->assertSeeInHtml('See all 1 photo')
            ->assertSeeInHtml('The Winter Wonderland')
            ->assertSeeInHtml('reply to this email');
    }

    public function test_only_admins_can_send_show_emails(): void
    {
        $show = $this->showWithLineup();

        $this->post("/shows/{$show->id}/lineup/announce")->assertRedirect('/login');
        $this->post("/shows/{$show->id}/recap")->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->post("/shows/{$show->id}/lineup/announce")->assertForbidden();
        $this->actingAs(User::factory()->create())->post("/shows/{$show->id}/recap")->assertForbidden();
    }

    public function test_send_buttons_appear_in_the_right_places(): void
    {
        $upcoming = $this->showWithLineup();
        Invite::factory()->attending()->for($upcoming)->create();
        $past = $this->showWithLineup(['date' => now()->subDay()]);
        Invite::factory()->attending()->for($past)->create();

        $this->actingAs($this->admin());
        $this->get("/shows/{$upcoming->id}/lineup")->assertSee('Email the Lineup');
        $this->get("/shows/{$upcoming->id}/view")->assertDontSee('Email the Recap');
        $this->get("/shows/{$past->id}/view")->assertSee('Email the Recap');
        $this->get("/shows/{$past->id}/lineup")->assertDontSee('Email the Lineup');
    }
}
