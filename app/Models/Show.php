<?php

namespace App\Models;

use App\Mail\WaitlistPromoted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Show extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime',
        'canceled' => 'boolean',
        'count_performers' => 'boolean',
        'lineup_announced_at' => 'datetime',
        'recap_sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Show $show) {
            $show->guest_link_key ??= (string) Str::uuid();
        });
    }

    /**
     * The secret link to share with guests. RSVPs through it are approved
     * automatically.
     */
    public function getGuestLinkAttribute(): string
    {
        return route('invites.join', $this->guest_link_key);
    }

    public function resetGuestLink(): void
    {
        $this->update(['guest_link_key' => (string) Str::uuid()]);
    }

    public function invites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function guests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function exhibitors(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Exhibitor::class);
    }

    public function lineup(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->exhibitors()->where('status', 'Approved')->orderBy('performance_order');
    }

    public function photos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShowPhoto::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now())->where('canceled', false);
    }

    public function isPast(): bool
    {
        return $this->date->isPast();
    }

    /**
     * Seats taken by attending guests, counting plus ones.
     */
    public function seatsTaken(?Invite $except = null): int
    {
        $guests = $this->invites()
            ->withResponse(Invite::ATTENDING)
            ->where('guest_request', false)
            ->when($except?->exists, fn ($query) => $query->whereKeyNot($except->id))
            ->get();

        return $guests->sum(fn (Invite $invite) => $invite->seats()) + $this->performerSeats($guests);
    }

    /**
     * Seats for acts in the lineup, when the show counts performers. A
     * performer who also RSVP'd as a guest already has a seat.
     */
    public function performerSeats(?\Illuminate\Support\Collection $guests = null): int
    {
        if (! $this->count_performers) {
            return 0;
        }

        $guests ??= $this->invites()->withResponse(Invite::ATTENDING)->where('guest_request', false)->get();

        return $this->lineup()
            ->whereNotIn('person_id', $guests->pluck('person_id')->filter()->all())
            ->count();
    }

    public function hasRoomFor(int $seats, ?Invite $except = null): bool
    {
        if ($this->max_attendants <= 0) {
            return true;
        }

        return $this->seatsTaken($except) + $seats <= $this->max_attendants;
    }

    /**
     * Move waitlisted guests into open seats, oldest first, and let them know.
     */
    public function promoteWaitlist(): void
    {
        $waitlist = $this->invites()
            ->withResponse(Invite::WAITLIST)
            ->where('guest_request', false)
            ->orderBy('waitlisted_at')
            ->orderBy('id')
            ->get();

        foreach ($waitlist as $invite) {
            if (! $this->hasRoomFor($invite->seats())) {
                continue;
            }

            $invite->update(['response_status' => Invite::ATTENDING, 'waitlisted_at' => null]);

            if ($invite->email) {
                Mail::to($invite->email)->send(new WaitlistPromoted($invite));
            }
        }
    }

    /**
     * Everyone invited who hasn't said no (and whose invite has gone out):
     * who hears about lineup news or a cancellation.
     */
    public function interestedGuests(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->invites()
            ->whereNotIn('response_status', [Invite::NO, 'CREATED'])
            ->whereNotNull('email')->where('email', '!=', '')
            ->get();
    }

    /**
     * Confirmed guests, who get the thank-you recap after the show.
     */
    public function recapRecipients(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->invites()
            ->withResponse(Invite::ATTENDING)
            ->where('guest_request', false)
            ->whereNotNull('email')->where('email', '!=', '')
            ->get();
    }

    public function getApplicationsWithStatus($status): \Illuminate\Database\Eloquent\Collection
    {
        return $this->submissionApplications()->where('approved', $status)->get();
    }

    public function submissionApplications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubmissionApplication::class);
    }

    public function getAttendingInvitesAttribute()
    {
        return $this->invites()->withResponse(Invite::ATTENDING)->where('guest_request', false)->get();
    }

    public function getAttendingInvitesWithPlusOneAttribute()
    {
        return $this->invites()->withResponse(Invite::ATTENDING)->where('guest_request', false)->where('plus_one_status', 1)->get();
    }

    public function getPendingRequestsAttribute()
    {
        return $this->invites()->where('guest_request', true)->get();
    }

    public function getWaitlistInvitesAttribute()
    {
        return $this->invites()->withResponse(Invite::WAITLIST)->orderBy('waitlisted_at')->get();
    }

    public function getPendingInvitesAttribute()
    {
        return $this->invites()->withPending()->get();
    }

    public function getNoInvitesAttribute()
    {
        return $this->invites()->withResponse(Invite::NO)->get();
    }

    public function getMaybeInvitesAttribute()
    {
        return $this->invites()->withResponse(Invite::MAYBE)->get();
    }

    public function getCreatedInvitesAttribute()
    {
        return $this->invites()->withResponse('CREATED')->get();
    }
}
