<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'people';

    protected $fillable = [
        'name',
        'contact_info',
        'email',
        'phone_number',
    ];

    /**
     * Find the person these details belong to, or create them. Matches on
     * email, then phone, then full name (a first name alone is too ambiguous).
     */
    public static function resolve(?string $name, ?string $email = null, ?string $phone = null): self
    {
        $name = trim(preg_replace('/\s+/', ' ', (string) $name)) ?: 'Unknown';
        $email = $email ? strtolower(trim($email)) : null;
        $phone = $phone ? preg_replace('/\D/', '', $phone) : null;
        $phone = strlen((string) $phone) >= 7 ? $phone : null;

        $person = null;

        if ($email) {
            $person = static::whereRaw('lower(email) = ?', [$email])->first();
        }

        if (! $person && $phone) {
            $person = static::where('phone_number', $phone)->first();
        }

        if (! $person && str_contains($name, ' ')) {
            $person = static::whereRaw('lower(name) = ?', [strtolower($name)])->first();
        }

        if (! $person) {
            return static::create(['name' => $name, 'email' => $email, 'phone_number' => $phone]);
        }

        $person->email ??= $email;
        $person->phone_number ??= $phone;
        $person->save();

        return $person;
    }

    /**
     * Fold a duplicate into this person: their invites, applications and
     * performances move over, missing contact details are filled in, and the
     * duplicate is removed.
     */
    public function absorb(Person $duplicate): void
    {
        DB::transaction(function () use ($duplicate) {
            foreach (['invites', 'submission_applications', 'exhibitors', 'guests'] as $table) {
                DB::table($table)->where('person_id', $duplicate->id)->update(['person_id' => $this->id]);
            }

            $this->email ??= $duplicate->email;
            $this->phone_number ??= $duplicate->phone_number;
            $this->contact_info ??= $duplicate->contact_info;
            $this->save();

            $duplicate->delete();
        });
    }

    /**
     * Reasons that only hint at a match. People linked only by these are
     * suggested but not pre-selected for merging.
     */
    public const WEAK_REASONS = ['same first name'];

    /**
     * Group people who are probably the same person: they share an email, a
     * phone number, a first and last name, or (more loosely) a first name.
     * Groups chain, so if A shares an email with B and B a phone with C, all
     * three are grouped. Pairs marked as not a match are never linked.
     *
     * Each group's `strong` lists the people linked to it by more than a
     * weak reason; they're the ones worth pre-selecting for a merge.
     *
     * @param  Collection<int, Person>  $people
     * @return Collection<int, array{people: Collection<int, Person>, reasons: list<string>, strong: list<int>}>
     */
    public static function duplicateGroups(Collection $people): Collection
    {
        $people = collect($people->values()->all());
        $notMatching = static::nonMatchingPairs();
        $parent = range(0, max($people->count() - 1, 0));
        $find = function (int $i) use (&$parent, &$find): int {
            return $parent[$i] === $i ? $i : $parent[$i] = $find($parent[$i]);
        };

        $buckets = [];
        foreach ($people as $i => $person) {
            foreach (static::matchKeys($person) as $reason => $key) {
                $buckets[$reason][$key][] = $i;
            }
        }

        $links = [];
        $strong = [];
        foreach ($buckets as $reason => $byKey) {
            foreach ($byKey as $members) {
                foreach ($members as $a => $i) {
                    foreach (array_slice($members, $a + 1) as $j) {
                        if (isset($notMatching[static::pairKey($people[$i]->id, $people[$j]->id)])) {
                            continue;
                        }

                        $parent[$find($j)] = $find($i);
                        $links[] = [$i, $reason];
                        if (! in_array($reason, static::WEAK_REASONS)) {
                            $strong[$i] = $strong[$j] = true;
                        }
                    }
                }
            }
        }

        $reasons = [];
        foreach ($links as [$i, $reason]) {
            $reasons[$find($i)][$reason] = true;
        }

        return $people->groupBy(fn ($person, $i) => $find($i), preserveKeys: true)
            ->filter(fn ($group) => $group->count() > 1)
            ->map(fn ($group, $root) => [
                'people' => $group->values(),
                'reasons' => collect(array_keys($reasons[$root] ?? []))->sort()->values()->all(),
                'strong' => $group->filter(fn ($person, $i) => isset($strong[$i]))->pluck('id')->values()->all(),
            ])
            // Strong matches first, then the first-name-only hunches.
            ->sortBy(fn ($group) => [$group['strong'] === [] ? 1 : 0, mb_strtolower($group['people']->first()->name)])
            ->values();
    }

    /**
     * The values two records of the same person are likely to share.
     *
     * @return array<string, string>
     */
    private static function matchKeys(Person $person): array
    {
        $keys = [];

        if ($email = strtolower(trim((string) $person->email))) {
            $keys['same email'] = $email;
        }

        // Compare the last ten digits so "+1 215…" matches "215…".
        $phone = substr(preg_replace('/\D/', '', (string) $person->phone_number), -10);
        if (strlen($phone) >= 7) {
            $keys['same phone'] = $phone;
        }

        // Names ignore case, punctuation and middle names.
        $words = preg_split('/\s+/', trim(preg_replace('/[^\pL\s]/u', '', mb_strtolower((string) $person->name))), -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) >= 2) {
            $keys['same name'] = $words[0].' '.end($words);
        }

        // Skip initials ("L Zhang") and the placeholder for missing names.
        if ($words && mb_strlen($words[0]) > 1 && $words[0] !== 'unknown') {
            $keys['same first name'] = $words[0];
        }

        return $keys;
    }

    /**
     * Record that each of the others is not the same person as this one.
     */
    public function markNotMatching(iterable $others): void
    {
        $now = now();

        $rows = collect($others)
            ->reject(fn (Person $other) => $other->is($this))
            ->map(fn (Person $other) => [
                'person_id' => min($this->id, $other->id),
                'other_person_id' => max($this->id, $other->id),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

        DB::table('person_non_matches')->insertOrIgnore($rows->values()->all());
    }

    /**
     * Forget that this person and another were marked as not matching.
     */
    public function forgetNotMatching(Person $other): void
    {
        DB::table('person_non_matches')
            ->where('person_id', min($this->id, $other->id))
            ->where('other_person_id', max($this->id, $other->id))
            ->delete();
    }

    /**
     * The people this person was marked as not matching.
     *
     * @return Collection<int, Person>
     */
    public function notMatching(): Collection
    {
        $ids = DB::table('person_non_matches')->where('person_id', $this->id)->pluck('other_person_id')
            ->merge(DB::table('person_non_matches')->where('other_person_id', $this->id)->pluck('person_id'));

        return static::whereKey($ids)->orderBy('name')->get();
    }

    /**
     * @return array<string, true>
     */
    private static function nonMatchingPairs(): array
    {
        return DB::table('person_non_matches')->get(['person_id', 'other_person_id'])
            ->mapWithKeys(fn ($pair) => [static::pairKey($pair->person_id, $pair->other_person_id) => true])
            ->all();
    }

    private static function pairKey(int $a, int $b): string
    {
        return min($a, $b).'-'.max($a, $b);
    }

    public function invites()
    {
        return $this->hasMany(Invite::class);
    }

    public function submissionApplications()
    {
        return $this->hasMany(SubmissionApplication::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function exhibitors()
    {
        return $this->hasMany(Exhibitor::class);
    }
}
