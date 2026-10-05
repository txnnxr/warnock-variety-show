<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ShowPhoto extends Model
{
    protected $fillable = [
        'show_id',
        'path',
        'caption',
    ];

    protected static function booted(): void
    {
        static::deleted(function (ShowPhoto $photo) {
            Storage::disk('public')->delete($photo->path);
        });
    }

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
