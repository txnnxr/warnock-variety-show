<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\Show;
use App\Models\SubmissionApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_the_next_show_and_what_needs_doing(): void
    {
        $past = Show::factory()->create(['name' => 'Summer Soiree', 'date' => now()->subWeek()]);
        Invite::factory()->attending()->for($past)->create();
        $next = Show::factory()->create(['name' => 'Autumn Spectacular']);
        Invite::factory()->attending()->for($next)->create(['guest_request' => true]);
        Invite::factory()->for($next)->count(2)->create();
        SubmissionApplication::factory()->for($next)->create();
        SubmissionApplication::factory()->for($next)->create()->approve();

        $this->actingAs(User::factory()->admin()->create())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Autumn Spectacular')
            ->assertSee('1 guest request waiting for approval')
            ->assertSee('2 invites not sent yet')
            ->assertSee('1 application to review')
            ->assertSee('The lineup hasn&#039;t been announced', false)
            ->assertSee('No recap sent for Summer Soiree');
    }

    public function test_finished_tasks_drop_off_the_list(): void
    {
        $past = Show::factory()->create(['date' => now()->subWeek(), 'recap_sent_at' => now()]);
        Invite::factory()->attending()->for($past)->create();
        $next = Show::factory()->create(['lineup_announced_at' => now()]);
        SubmissionApplication::factory()->for($next)->create()->approve();

        $this->actingAs(User::factory()->admin()->create())
            ->get('/dashboard')
            ->assertSee('All caught up.');
    }

    public function test_dashboard_without_an_upcoming_show(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/dashboard')
            ->assertSee('Plan the Next Show');
    }

    public function test_non_admins_see_a_plain_dashboard(): void
    {
        Show::factory()->create(['name' => 'Autumn Spectacular']);

        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertOk()
            ->assertDontSee('Autumn Spectacular')
            ->assertDontSee('To Do');
    }

    public function test_admins_get_a_dashboard_link_in_the_menu(): void
    {
        $link = 'href="'.route('dashboard').'">Dashboard</a>';

        $this->actingAs(User::factory()->admin()->create())->get('/')->assertSee($link, false);
        $this->actingAs(User::factory()->create())->get('/')->assertDontSee($link, false);
        auth()->logout();
        $this->get('/')->assertDontSee($link, false);
    }
}
