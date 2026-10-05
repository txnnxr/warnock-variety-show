<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Link existing invites and applications to people, and turn already
     * approved applications into lineup entries. Mirrors Person::resolve(),
     * but uses the query builder so later model changes can't break it.
     */
    public function up(): void
    {
        DB::table('invites')->whereNull('person_id')->orderBy('id')->each(function ($invite) {
            $name = implode(' ', array_filter([$invite->first_name, $invite->middle_name, $invite->last_name]));

            DB::table('invites')->where('id', $invite->id)->update([
                'person_id' => $this->resolvePerson($name, $invite->email, $invite->phone),
            ]);
        });

        DB::table('submission_applications')->whereNull('person_id')->orderBy('id')->each(function ($application) {
            DB::table('submission_applications')->where('id', $application->id)->update([
                'person_id' => $this->resolvePerson($application->name, $application->email, $application->phone),
            ]);
        });

        DB::table('submission_applications')
            ->where('approved', 1)
            ->whereNull('deleted_at')
            ->orderBy('show_id')
            ->orderBy('id')
            ->each(function ($application) {
                if (DB::table('exhibitors')->where('submission_application_id', $application->id)->exists()) {
                    return;
                }

                $now = now();

                DB::table('exhibitors')->insert([
                    'person_id' => $application->person_id,
                    'show_id' => $application->show_id,
                    'submission_application_id' => $application->id,
                    'exhibition_description' => $application->title ?: 'Untitled',
                    'status' => 'Approved',
                    'performance_order' => DB::table('exhibitors')->where('show_id', $application->show_id)->max('performance_order') + 1,
                    'plus_one' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });
    }

    public function down(): void
    {
        DB::table('exhibitors')->whereNotNull('submission_application_id')->delete();
        DB::table('submission_applications')->update(['person_id' => null]);
        DB::table('invites')->update(['person_id' => null]);
    }

    private function resolvePerson(?string $name, ?string $email, ?string $phone): int
    {
        $name = trim(preg_replace('/\s+/', ' ', (string) $name)) ?: 'Unknown';
        $email = $email ? strtolower(trim($email)) : null;
        $phone = $phone ? preg_replace('/\D/', '', $phone) : null;
        $phone = strlen((string) $phone) >= 7 ? $phone : null;

        $person = null;

        if ($email) {
            $person = DB::table('people')->whereRaw('lower(email) = ?', [$email])->first();
        }

        if (! $person && $phone) {
            $person = DB::table('people')->where('phone_number', $phone)->first();
        }

        if (! $person && str_contains($name, ' ')) {
            $person = DB::table('people')->whereRaw('lower(name) = ?', [strtolower($name)])->first();
        }

        if ($person) {
            $missing = array_filter([
                'email' => $person->email ? null : $email,
                'phone_number' => $person->phone_number ? null : $phone,
            ]);

            if ($missing) {
                DB::table('people')->where('id', $person->id)->update($missing);
            }

            return $person->id;
        }

        $now = now();

        return DB::table('people')->insertGetId([
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
};
