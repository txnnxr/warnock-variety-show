<?php

namespace Tests\Feature;

use App\Models\Invite;
use App\Models\Person;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    public function test_resolve_matches_on_email_case_insensitively(): void
    {
        $person = Person::resolve('Ada Lovelace', 'Ada@Example.com');

        $this->assertTrue($person->is(Person::resolve('A. Lovelace', 'ada@example.com')));
    }

    public function test_resolve_matches_on_phone_digits(): void
    {
        $person = Person::resolve('Ada Lovelace', null, '(555) 123-4567');

        $this->assertTrue($person->is(Person::resolve('Ada', null, '555.123.4567')));
    }

    public function test_resolve_matches_full_names_but_not_first_names_alone(): void
    {
        $this->assertTrue(Person::resolve('Ada Lovelace')->is(Person::resolve('ada  lovelace')));
        $this->assertFalse(Person::resolve('Sam')->is(Person::resolve('Sam')));
    }

    public function test_resolve_fills_in_missing_contact_details(): void
    {
        $person = Person::resolve('Ada Lovelace');
        Person::resolve('Ada Lovelace', 'ada@example.com');

        $this->assertSame('ada@example.com', $person->fresh()->email);
    }

    public function test_person_page_shows_their_history(): void
    {
        $person = Person::resolve('Ada Lovelace', 'ada@example.com');
        $show = Show::factory()->create(['name' => 'Autumn Show']);
        Invite::factory()->attending()->for($show)->create(['person_id' => $person->id]);

        $this->actingAs(User::factory()->admin()->create())
            ->get("/people/{$person->id}")
            ->assertOk()
            ->assertSee('Autumn Show');
    }
}
