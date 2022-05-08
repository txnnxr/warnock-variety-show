<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\ICS;
use Carbon\Carbon;

class Invite extends Model
{
    use HasFactory;

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

    public function generateICS(){
        header('Content-Type: text/calendar; charset=utf-8');
        header("Content-Disposition: inline; filename={$this->show->name}.ics");

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
}
