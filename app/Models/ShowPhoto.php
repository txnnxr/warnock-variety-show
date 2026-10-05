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

    /**
     * What the show page's photo gallery needs to display this photo.
     */
    public function toGallery(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'caption' => $this->caption,
            'alt' => $this->caption ?? $this->show->name,
            'delete_url' => route('photos.destroy', $this),
        ];
    }
}
