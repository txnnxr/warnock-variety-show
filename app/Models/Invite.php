<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\ICS;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invite extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function show() {
        return $this->belongsTo(Show::class);
    }

    public function getLinkAttribute(){
        return config('app.url').'/shows/'.$this->show->id.'/invite/respond/'.$this->key;
    }

    public function scopeWithResponse($query, $response)
    {
        return $query->where('response_status', $response);
    }

    public function scopeWithPending($query)
    {
        return $query->where('response_status', 'like', 'PENDING%');
    }

    public function scopeWithAttendingTalent($query)
    {
        return $query->withResponse('ATTENDING')->where('talent', 1);
    }

    public function scopeOnWaitlist($query, $waitlistType) {
        return $query->whereNotNull($waitlistType.'_waitlist_priority');
    }

    public function isOnAttendingWaitlist(): bool
    {
        return $this->response_status == 'ATTENDING' && $this->attendance_waitlist_priority !== null;
    }

    public function isOnTalentWaitlist(): bool
    {
        return $this->talent && $this->talent_waitlist_priority !== null;
    }

    public function generateICS(){
        header('Content-Type: text/calendar; charset=utf-8');
        header("Content-Disposition: attachment; filename={$this->show->name}.ics");

        $ics = new ICS(array(
            'location' => $this->show->address,
            'description' => preg_replace('/[\n\r]+/', '', $this->show->description),
            'dtstart' => $this->show->date,
            'dtend' => Carbon::parse($this->show->date)->addHours(3)->toDateTimeString(),
            'summary' => "Warnock Variety Show - " . $this->show->name,
            'url' => $this->link
        ));

        echo $ics->to_string();
    }

    public function isMobileDevice() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo
        |fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
        , $_SERVER["HTTP_USER_AGENT"]);
    }
}
