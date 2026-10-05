<?php

namespace Tests\Feature;

use App\Models\Show;
use App\Models\SubmissionApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmissionApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_apply_and_check_their_application_by_key(): void
    {
        $show = Show::factory()->create();

        $response = $this->post("/shows/{$show->id}/submission-applications", [
            'name' => 'Pat Juggler',
            'title' => 'Fire Juggling',
            'email' => 'pat@example.com',
        ]);

        $application = SubmissionApplication::firstOrFail();
        $response->assertRedirect("/applications/{$application->key}");
        $this->assertSame('Pat Juggler', $application->person->name);
        $this->get("/applications/{$application->key}")->assertOk()->assertSee('Fire Juggling');
    }

    public function test_applications_require_a_title(): void
    {
        $show = Show::factory()->create();

        $this->post("/shows/{$show->id}/submission-applications", ['name' => 'Pat'])
            ->assertSessionHasErrors('title');
    }

    public function test_approve_and_deny_are_post_only_and_admin_only(): void
    {
        $application = SubmissionApplication::factory()->create();

        $this->get("/submission-applications/{$application->id}/approve")->assertMethodNotAllowed();
        $this->post("/submission-applications/{$application->id}/approve")->assertRedirect('/login');
        $this->actingAs(User::factory()->create())
            ->post("/submission-applications/{$application->id}/approve")
            ->assertForbidden();

        $this->assertFalse($application->fresh()->approved);
    }

    public function test_approving_adds_the_act_to_the_end_of_the_lineup(): void
    {
        $admin = User::factory()->admin()->create();
        $show = Show::factory()->create();
        $first = SubmissionApplication::factory()->for($show)->create(['title' => 'Tap Dancing']);
        $second = SubmissionApplication::factory()->for($show)->create(['title' => 'Sound Bath']);

        $this->actingAs($admin)->post("/submission-applications/{$first->id}/approve");
        $this->actingAs($admin)->post("/submission-applications/{$second->id}/approve");

        $this->assertSame(['Tap Dancing', 'Sound Bath'], $show->lineup()->pluck('exhibition_description')->all());
        $this->get("/shows/{$show->id}/view")->assertSeeInOrder(['Tap Dancing', 'Sound Bath']);
    }

    public function test_denying_removes_the_act_from_the_lineup(): void
    {
        $admin = User::factory()->admin()->create();
        $application = SubmissionApplication::factory()->create();

        $this->actingAs($admin)->post("/submission-applications/{$application->id}/approve");
        $this->actingAs($admin)->post("/submission-applications/{$application->id}/deny");

        $this->assertFalse($application->fresh()->approved);
        $this->assertSame('Denied', $application->exhibitor->status);
        $this->assertCount(0, $application->show->lineup);
    }

    public function test_reapproving_does_not_duplicate_the_act(): void
    {
        $admin = User::factory()->admin()->create();
        $application = SubmissionApplication::factory()->create();

        $this->actingAs($admin)->post("/submission-applications/{$application->id}/approve");
        $this->actingAs($admin)->post("/submission-applications/{$application->id}/approve");

        $this->assertCount(1, $application->show->lineup);
    }

    public function test_admin_can_reorder_the_lineup(): void
    {
        $admin = User::factory()->admin()->create();
        $show = Show::factory()->create();
        foreach (['First', 'Second', 'Third'] as $title) {
            SubmissionApplication::factory()->for($show)->create(['title' => $title])->approve();
        }
        $third = $show->lineup()->where('exhibition_description', 'Third')->first();

        $this->actingAs($admin)->post("/lineup/{$third->id}/up")->assertRedirect("/shows/{$show->id}/lineup");

        $this->assertSame(['First', 'Third', 'Second'], $show->lineup()->pluck('exhibition_description')->all());
        $this->assertSame([1, 2, 3], $show->lineup()->pluck('performance_order')->all());
    }

    public function test_applications_list_handles_a_show_with_none(): void
    {
        $show = Show::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get("/shows/{$show->id}/submission-applications")
            ->assertOk();
    }
}
