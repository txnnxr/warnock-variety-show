<?php

namespace Tests\Feature;

use App\Mail\Invitation;
use App\Models\Invite;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_email_an_invite(): void
    {
        Mail::fake();
        $invite = Invite::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->post("/invites/{$invite->id}/send")
            ->assertRedirect();

        Mail::assertSent(Invitation::class, fn ($mail) => $mail->hasTo($invite->email));
        $this->assertSame('PENDING - SENT', $invite->fresh()->response_status);
    }

    public function test_send_all_only_emails_unsent_invites_with_an_address(): void
    {
        Mail::fake();
        $show = Show::factory()->create();
        $unsent = Invite::factory()->for($show)->create();
        $noEmail = Invite::factory()->for($show)->create(['email' => null]);
        $alreadySent = Invite::factory()->for($show)->create(['response_status' => 'PENDING - SENT']);

        $this->actingAs(User::factory()->admin()->create())
            ->post("/shows/{$show->id}/invite/send-all");

        Mail::assertSent(Invitation::class, 1);
        Mail::assertSent(Invitation::class, fn ($mail) => $mail->hasTo($unsent->email));
        $this->assertSame('CREATED', $noEmail->fresh()->response_status);
    }

    public function test_the_invitation_does_not_include_the_address(): void
    {
        $invite = Invite::factory()->create();

        (new Invitation($invite))->assertDontSeeInHtml($invite->show->address)->assertSeeInHtml($invite->key);
    }

    public function test_invites_created_by_the_admin_are_linked_to_a_person(): void
    {
        $show = Show::factory()->create();

        $this->actingAs(User::factory()->admin()->create())->post("/shows/{$show->id}/invite", [
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
        ]);

        $this->assertSame('Ada Lovelace', Invite::firstOrFail()->person->name);
    }
}
