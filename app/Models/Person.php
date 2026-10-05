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
     * Group people who are probably the same person: they share an email,
     * a phone number, or a first and last name. Groups chain, so if A shares
     * an email with B and B a phone with C, all three are grouped.
     *
     * @param  Collection<int, Person>  $people
     * @return Collection<int, array{people: Collection<int, Person>, reasons: list<string>}>
     */
    public static function duplicateGroups(Collection $people): Collection
    {
        $people = collect($people->values()->all());
        $parent = range(0, max($people->count() - 1, 0));
        $find = function (int $i) use (&$parent, &$find): int {
            return $parent[$i] === $i ? $i : $parent[$i] = $find($parent[$i]);
        };

        $seen = [];
        $reasons = [];

        foreach ($people as $i => $person) {
            foreach (static::matchKeys($person) as $reason => $key) {
                if (! isset($seen[$reason][$key])) {
                    $seen[$reason][$key] = $i;

                    continue;
                }

                $root = $find($seen[$reason][$key]);
                $parent[$find($i)] = $root;
                $reasons[$root][$reason] = true;
            }
        }

        return $people->groupBy(fn ($person, $i) => $find($i), preserveKeys: true)
            ->filter(fn ($group) => $group->count() > 1)
            ->map(fn ($group, $root) => [
                'people' => $group->values(),
                // Reasons were recorded under whichever root was current at
                // the time, so gather them from every member of the group.
                'reasons' => collect($group->keys())->flatMap(fn ($i) => array_keys($reasons[$i] ?? []))->unique()->sort()->values()->all(),
            ])
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

        // First and last name, ignoring case, punctuation and middle names.
        // A first name alone is too common to go on.
        $words = preg_split('/\s+/', trim(preg_replace('/[^\pL\s]/u', '', mb_strtolower((string) $person->name))), -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) >= 2) {
            $keys['same name'] = $words[0].' '.end($words);
        }

        return $keys;
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
