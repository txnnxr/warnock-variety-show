<?php

namespace Tests\Feature;

use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_login_from_admin_pages(): void
    {
        $show = Show::factory()->create();

        $this->get('/shows')->assertRedirect('/login');
        $this->get("/shows/{$show->id}/invite")->assertRedirect('/login');
        $this->get('/people')->assertRedirect('/login');
    }

    public function test_non_admin_users_are_forbidden_from_admin_pages(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create();

        $this->actingAs($user)->get('/shows')->assertForbidden();
        $this->actingAs($user)->get("/shows/{$show->id}/invite")->assertForbidden();
        $this->actingAs($user)->get("/shows/{$show->id}/submission-applications")->assertForbidden();
        $this->actingAs($user)->get('/people')->assertForbidden();
    }

    public function test_admins_can_reach_admin_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $show = Show::factory()->create();

        $this->actingAs($admin)->get('/shows')->assertOk();
        $this->actingAs($admin)->get("/shows/{$show->id}/invite")->assertOk();
        $this->actingAs($admin)->get("/shows/{$show->id}/submission-applications")->assertOk();
        $this->actingAs($admin)->get("/shows/{$show->id}/lineup")->assertOk();
        $this->actingAs($admin)->get('/people')->assertOk();
    }

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'Intruder',
            'email' => 'intruder@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound();

        $this->assertDatabaseMissing('users', ['email' => 'intruder@example.com']);
    }

    public function test_make_admin_command_creates_an_admin(): void
    {
        $this->artisan('app:make-admin', ['email' => 'host@example.com'])
            ->expectsQuestion('Name', 'Host')
            ->expectsQuestion('Password', 'correct-horse')
            ->assertSuccessful();

        $this->assertTrue(User::where('email', 'host@example.com')->first()->is_admin);
    }

    public function test_make_admin_command_promotes_an_existing_user(): void
    {
        $user = User::factory()->create(['email' => 'friend@example.com']);

        $this->artisan('app:make-admin', ['email' => 'friend@example.com'])->assertSuccessful();

        $this->assertTrue($user->fresh()->is_admin);
    }
}
