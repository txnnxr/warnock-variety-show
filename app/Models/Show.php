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

    public function invites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function getAttendingInvitesAttribute()
    {
        return $this->invites()->withResponse('ATTENDING')->get();
    }

    public function getPendingInvitesAttribute()
    {
        return $this->invites()->withPending()->get();
    }

    public function getNoInvitesAttribute()
    {
        return $this->invites()->withResponse('COWARD')->get();
    }

    public function getMaybeInvitesAttribute()
    {
        return $this->invites()->withResponse('NO')->get();
    }

    public function getCreatedInvitesAttribute()
    {
        return $this->invites()->withResponse('CREATED')->get();
    }
}
