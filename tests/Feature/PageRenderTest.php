<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\Show;
use App\Models\SubmissionApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_render_with_data(): void
    {
        $admin = User::factory()->admin()->create();
        $show = Show::factory()->create(['max_attendants' => 1]);
        Invite::factory()->attending()->for($show)->create(['first_name' => 'Attendee']);
        Invite::factory()->for($show)->create(['first_name' => 'Waiting', 'response_status' => Invite::WAITLIST, 'waitlisted_at' => now()]);
        $application = SubmissionApplication::factory()->for($show)->create(['title' => 'Sword Swallowing']);
        $application->approve();

        $this->actingAs($admin);
        $this->get("/shows/{$show->id}/invite")->assertOk()->assertSee('Email')->assertSee('Waitlist: 1');
        $this->get("/shows/{$show->id}/lineup")->assertOk()->assertSee('Sword Swallowing');
        $this->get("/shows/{$show->id}/submission-applications")->assertOk()->assertSee($application->name);
        $this->get("/shows/{$show->id}/submission-applications/{$application->id}/view")->assertOk()->assertSee('Approve');
        $this->get("/shows/{$show->id}/view")->assertOk()->assertSee('Waitlist (1)')->assertSee('Upload Photos');
        $this->get('/people')->assertOk()->assertSee($application->name);
        $this->get('/shows')->assertOk()->assertSee($show->name);
    }

    public function test_public_show_page_hides_admin_controls(): void
    {
        $show = Show::factory()->create();
        $application = SubmissionApplication::factory()->for($show)->create(['title' => 'Secret Limbo Act']);

        $this->get("/shows/{$show->id}/view")
            ->assertOk()
            ->assertDontSee('Upload Photos')
            ->assertDontSee('Secret Limbo Act')
            ->assertDontSee('View Submissions');
    }

    public function test_dashboard_and_profile_render_their_content(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertOk()->assertSee("You're logged in!", false);
        $this->actingAs($user)->get('/profile')->assertOk()->assertSee('Profile Information');
    }

    public function test_home_page_renders_with_an_upcoming_show(): void
    {
        $show = Show::factory()->create(['date' => now()->addDays(10)]);

        $this->get('/')->assertOk()->assertSee($show->name);
    }
}
