<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendee extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function invites()
    {
        return $this->hasMany(Invite::class);
    }

    public function shows()
    {
        return $this->hasManyThrough(Show::class, Invite::class);
    }

    public function points()
    {
        $points = 0;
        foreach($this->invites as $invite) {
            if ($invite->response_status == 'ATTENDING') {
                switch ($invite->attendance_status) {
                    case 'LATE':
                        $points--;
                        break;
                    case 'CANCELLED DAY OF':
                        $points -= 2;
                        break;
                    case 'NO CALL NO SHOW':
                        $points -= 3;
                        break;
                    case 'ON TIME':
                    case 'LATE WITH TALENT':
                        $points++;
                        break;
                    case 'ON TIME WITH TALENT':
                        $points += 2;
                        break;
                }
            } else if ($invite->response_status == 'COWARD') {
                switch ($invite->attendance_status) {
                    case 'LATE':
                        $points--;
                        break;
                    case 'CANCELLED DAY OF':
                        $points -= 1;
                        break;
                    case 'NO CALL NO SHOW':
                        $points -= 2;
                        break;
                    case 'ON TIME':
                    case 'ON TIME WITH TALENT':
                        $points++;
                        break;
                }
            }
        }
        return $points;
    }
}
