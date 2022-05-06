<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
