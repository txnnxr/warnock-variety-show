<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\Person;
use App\Models\Show;
use App\Models\SubmissionApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CountPerformersTest extends TestCase
{
    use RefreshDatabase;

    public function test_performers_do_not_take_seats_by_default(): void
    {
        $show = Show::factory()->create(['max_attendants' => 1]);
        SubmissionApplication::factory()->for($show)->create()->approve();
        $invite = Invite::factory()->for($show)->create();

        $invite->respond(Invite::ATTENDING);

        $this->assertSame(Invite::ATTENDING, $invite->fresh()->response_status);
    }

    public function test_performers_take_seats_when_the_show_counts_them(): void
    {
        $show = Show::factory()->create(['max_attendants' => 2, 'count_performers' => true]);
        SubmissionApplication::factory()->for($show)->count(2)->create()->each->approve();
        $invite = Invite::factory()->for($show)->create();

        $invite->respond(Invite::ATTENDING);

        $this->assertSame(2, $show->seatsTaken());
        $this->assertSame(Invite::WAITLIST, $invite->fresh()->response_status);
    }

    public function test_a_performer_who_rsvpd_is_not_counted_twice(): void
    {
        $show = Show::factory()->create(['max_attendants' => 10, 'count_performers' => true]);
        $person = Person::resolve('Pat Juggler', 'pat@example.com');
        SubmissionApplication::factory()->for($show)->create(['person_id' => $person->id])->approve();
        Invite::factory()->attending()->for($show)->create(['person_id' => $person->id]);

        $this->assertSame(1, $show->seatsTaken());
    }

    public function test_denying_an_act_frees_its_seat_for_the_waitlist(): void
    {
        Mail::fake();
        $show = Show::factory()->create(['max_attendants' => 1, 'count_performers' => true]);
        $application = SubmissionApplication::factory()->for($show)->create();
        $application->approve();
        $waiting = Invite::factory()->for($show)->create(['response_status' => Invite::WAITLIST, 'waitlisted_at' => now()]);

        $this->actingAs(User::factory()->admin()->create())->post("/submission-applications/{$application->id}/deny");

        $this->assertSame(Invite::ATTENDING, $waiting->fresh()->response_status);
    }

    public function test_the_setting_is_saved_from_the_show_form(): void
    {
        $show = Show::factory()->create();
        $admin = User::factory()->admin()->create();
        $fields = ['name' => $show->name, 'date' => $show->date, 'max_attendants' => 30, 'address' => $show->address];

        $this->actingAs($admin)->put("/shows/{$show->id}", $fields + ['count_performers' => 1]);
        $this->assertTrue($show->fresh()->count_performers);

        $this->actingAs($admin)->put("/shows/{$show->id}", $fields + ['count_performers' => 0]);
        $this->assertFalse($show->fresh()->count_performers);
    }
}
