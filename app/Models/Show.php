<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Show extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date'
    ];

    // TODO: Attributes may be getting crazy. Might want to clean them up.

    public function invites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function getAttendingInvitesAttribute()
    {
        return $this->invites()->withResponse('ATTENDING')->get();
    }

    public function getAttendingWaitlistInvitesAttribute()
    {
        return $this->invites()->withResponse('ATTENDING')->onWaitlist('attendance')->get();
    }

    public function getTalentWaitlistInvitesAttribute()
    {
        return $this->invites()->withResponse('ATTENDING')->onWaitlist('talent')->get();
    }

    public function getPendingInvitesAttribute()
    {
        return $this->invites()->withPending()->get();
    }

    public function getNoInvitesAttribute()
    {
        return $this->invites()->withResponse('NO')->get();
    }

    public function getMaybeInvitesAttribute()
    {
        return $this->invites()->withResponse('COWARD')->get();
    }

    public function getCreatedInvitesAttribute()
    {
        return $this->invites()->withResponse('CREATED')->get();
    }

    public function getAtCapacityAttendantsAttribute(): bool
    {
        return ($this->attending_invites->count() + $this->attending_invites->sum('plus_one_status'))>= $this->max_attendants;
    }

    public function attendingTalents(){
        return $this->invites()->withAttendingTalent()->whereNull('talent_waitlist_priority')->get();
    }

    public function getAtCapacityTalentsAttribute(): bool
    {
        return $this->invites()->withAttendingTalent()->count() >= $this->max_talents;
    }

    public function getNextWaitlistPriority($type) {
        $waitlistCount = $this->invites->max($type.'_waitlist_priority');
        if ($waitlistCount !== null) {
            return $waitlistCount + 1;
        }
        return 0;
    }
}
