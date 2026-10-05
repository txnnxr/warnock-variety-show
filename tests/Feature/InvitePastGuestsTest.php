<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\Person;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitePastGuestsTest extends TestCase
{
    use RefreshDatabase;

    private function attendee(Show $show, string $name, ?string $email = null): Invite
    {
        $person = Person::resolve($name, $email);
        [$first, $last] = explode(' ', $name);

        return Invite::factory()->attending()->for($show)->create(['person_id' => $person->id, 'first_name' => $first, 'last_name' => $last, 'email' => $email]);
    }

    public function test_invites_everyone_who_attended_a_past_show(): void
    {
        $spring = Show::factory()->create(['date' => now()->subMonths(2)]);
        $summer = Show::factory()->create(['date' => now()->subMonth()]);
        $next = Show::factory()->create();
        $this->attendee($spring, 'Ada Lovelace', 'ada@example.com');
        $this->attendee($summer, 'Grace Hopper', 'grace@example.com');
        Invite::factory()->for($summer)->create(['response_status' => Invite::NO]);
        Invite::factory()->attending()->for($summer)->create(['guest_request' => true]);

        $this->actingAs(User::factory()->admin()->create())
            ->post("/shows/{$next->id}/invite/past-guests", ['source' => $summer->id])
            ->assertSessionHas('status', 'Added 1 invite. Use Email All Unsent Invites to send them.');

        $invite = $next->invites()->sole();
        $this->assertSame('Grace', $invite->first_name);
        $this->assertSame('CREATED', $invite->response_status);
        $this->assertNotSame($summer->invites()->first()->key, $invite->key);
    }

    public function test_everyone_option_dedupes_people_and_skips_existing_invites(): void
    {
        $spring = Show::factory()->create(['date' => now()->subMonths(2)]);
        $summer = Show::factory()->create(['date' => now()->subMonth()]);
        $next = Show::factory()->create();
        $this->attendee($spring, 'Ada Lovelace', 'ada@example.com');
        $this->attendee($summer, 'Ada Lovelace', 'ada@example.com');
        $this->attendee($summer, 'Grace Hopper', 'grace@example.com');
        Invite::factory()->for($next)->create(['email' => 'GRACE@example.com']);

        $this->actingAs(User::factory()->admin()->create())
            ->post("/shows/{$next->id}/invite/past-guests", ['source' => 'all'])
            ->assertSessionHas('status', 'Added 1 invite (1 already invited). Use Email All Unsent Invites to send them.');

        $this->assertSame(2, $next->invites()->count());
        $this->assertSame(1, $next->invites()->where('email', 'ada@example.com')->count());
    }

    public function test_upcoming_and_canceled_shows_are_not_sources(): void
    {
        $other = Show::factory()->create();
        $canceled = Show::factory()->create(['date' => now()->subMonth(), 'canceled' => true]);
        $next = Show::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post("/shows/{$next->id}/invite/past-guests", ['source' => $other->id])->assertSessionHasErrors('source');
        $this->actingAs($admin)->post("/shows/{$next->id}/invite/past-guests", ['source' => $canceled->id])->assertSessionHasErrors('source');
    }

    public function test_only_admins_can_invite_past_guests(): void
    {
        $next = Show::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post("/shows/{$next->id}/invite/past-guests", ['source' => 'all'])
            ->assertForbidden();
    }
}
