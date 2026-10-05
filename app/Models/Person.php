<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
