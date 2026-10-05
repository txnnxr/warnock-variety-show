<?php

namespace App\Models;

use App\Libraries\ICS;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invite extends Model
{
    use HasFactory, SoftDeletes;

    public const ATTENDING = 'ATTENDING';
    public const MAYBE = 'COWARD';
    public const NO = 'NO';
    public const WAITLIST = 'WAITLIST';

    /**
     * Every response status, with the label admins see.
     */
    public const STATUSES = [
        'CREATED' => 'Not sent',
        'PENDING - SENT' => 'Sent',
        'PENDING - OPENED' => 'Opened',
        'PENDING - UPDATE' => 'Changing response',
        self::ATTENDING => 'Attending',
        self::MAYBE => 'Maybe',
        self::NO => 'Not coming',
        self::WAITLIST => 'Waitlist',
    ];

    protected $guarded = [];

    protected $casts = [
        'talent' => 'boolean',
        'guest_request' => 'boolean',
        'has_plus_one_option' => 'boolean',
        'plus_one_status' => 'boolean',
        'waitlisted_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'nudge_sent_at' => 'datetime',
    ];

    public function show() {
        return $this->belongsTo(Show::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function getLinkAttribute(){
        return config('app.url').'/shows/'.$this->show->id.'/invite/respond/'.$this->key;
    }

    public function getFullNameAttribute(): string
    {
        return trim(preg_replace('/\s+/', ' ', "{$this->first_name} {$this->middle_name} {$this->last_name}"));
    }

    /**
     * Seats this invite takes at the show when attending.
     */
    public function seats(): int
    {
        return $this->plus_one_status ? 2 : 1;
    }

    /**
     * Confirmed guests get the address; pending guest requests and the
     * waitlist do not.
     */
    public function canSeeAddress(): bool
    {
        return $this->holdsSeat();
    }

    /**
     * Record a response. An "attending" response goes on the waitlist when
     * the show is full, and giving up a seat promotes the waitlist. Guest
     * requests hold no seat until they're approved.
     */
    public function respond(string $status, ?bool $plusOne = null, ?bool $talent = null): void
    {
        $wasAttending = $this->holdsSeat();

        if ($plusOne !== null) {
            $this->plus_one_status = $plusOne;
        }

        if ($talent !== null) {
            $this->talent = $talent;
        }

        if ($status === self::ATTENDING && ! $this->guest_request && ! $this->show->hasRoomFor($this->seats(), $this)) {
            $this->response_status = self::WAITLIST;
            $this->waitlisted_at ??= now();
        } else {
            $this->response_status = $status;
            $this->waitlisted_at = null;
        }

        $this->save();

        if ($wasAttending && ! $this->holdsSeat()) {
            $this->show->promoteWaitlist();
        }
    }

    /**
     * Approve a guest request. It takes a seat if one is free, otherwise it
     * joins the waitlist.
     */
    public function approveGuestRequest(): void
    {
        $this->guest_request = false;

        if ($this->response_status === self::ATTENDING && ! $this->show->hasRoomFor($this->seats(), $this)) {
            $this->response_status = self::WAITLIST;
            $this->waitlisted_at = now();
        }

        $this->save();
    }

    public function holdsSeat(): bool
    {
        return $this->response_status === self::ATTENDING && ! $this->guest_request;
    }

    public function scopeWithResponse($query, $response)
    {
        return $query->where('response_status', $response);
    }

    public function scopeWithPending($query)
    {
        return $query->where('response_status', 'like', 'PENDING%');
    }

    public function toICS(): string
    {
        $ics = new ICS(array(
            'location' => $this->show->address,
            'description' => preg_replace('/[\n\r]+/', '', $this->show->description),
            'dtstart' => $this->show->date,
            'dtend' => Carbon::parse($this->show->date)->addHours(3)->toDateTimeString(),
            'summary' => "Warnock Variety Show - " . $this->show->name,
            'url' => $this->link
        ));

        return $ics->to_string();
    }
}
