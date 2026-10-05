<?php

namespace Tests\Feature;

use App\Models\Exhibitor;
use App\Models\Invite;
use App\Models\Person;
use App\Models\Show;
use App\Models\SubmissionApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BackfillPeopleMigrationTest extends TestCase
{
    use RefreshDatabase;

    private function runBackfill(): void
    {
        $migration = require database_path('migrations/2026_10_04_000004_backfill_people_from_invites_and_applications.php');
        $migration->up();
    }

    public function test_existing_invites_and_applications_are_linked_to_people(): void
    {
        $show = Show::factory()->create();
        $invite = Invite::factory()->for($show)->create(['first_name' => 'Ada', 'last_name' => 'Lovelace', 'email' => 'ada@example.com']);
        $application = SubmissionApplication::factory()->for($show)->create(['name' => 'Ada Lovelace', 'email' => 'ADA@example.com', 'approved' => true]);
        DB::table('invites')->update(['person_id' => null]);
        DB::table('submission_applications')->update(['person_id' => null]);
        Person::query()->forceDelete();

        $this->runBackfill();

        $this->assertSame(1, Person::count());
        $this->assertNotNull($invite->fresh()->person_id);
        $this->assertSame($invite->fresh()->person_id, $application->fresh()->person_id);
    }

    public function test_approved_applications_become_lineup_entries_once(): void
    {
        $show = Show::factory()->create();
        SubmissionApplication::factory()->for($show)->create(['title' => 'Poetry', 'approved' => true]);
        SubmissionApplication::factory()->for($show)->create(['title' => 'Limbo Act', 'approved' => false]);

        $this->runBackfill();
        $this->runBackfill();

        $this->assertSame(['Poetry'], Exhibitor::pluck('exhibition_description')->all());
    }

    public function test_first_name_only_invites_are_not_merged(): void
    {
        Invite::factory()->create(['first_name' => 'Sam', 'last_name' => null, 'email' => null]);
        Invite::factory()->create(['first_name' => 'Sam', 'last_name' => null, 'email' => null]);

        $this->runBackfill();

        $this->assertSame(2, Person::count());
    }
}
