<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SubmissionApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'approved' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (SubmissionApplication $application) {
            $application->key ??= (string) Str::uuid();
        });
    }

    /**
     * @return array
     */
    public function getStatus(): string
    {
        return $this->approved ? "Approved" : "Limbo";
    }

    public function show(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Show::class);
    }

    public function person(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function exhibitor(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Exhibitor::class);
    }

    /**
     * Approve the application and put it at the end of the show's lineup.
     */
    public function approve(): void
    {
        $this->update(['approved' => true]);

        $this->person_id ??= Person::resolve($this->name, $this->email, $this->phone)->id;
        $this->save();

        $exhibitor = Exhibitor::withTrashed()->firstOrNew(['submission_application_id' => $this->id]);

        if (! $exhibitor->exists || $exhibitor->status !== 'Approved' || $exhibitor->trashed()) {
            $exhibitor->performance_order = $this->show->lineup()->max('performance_order') + 1;
        }

        $exhibitor->fill([
            'person_id' => $this->person_id,
            'show_id' => $this->show_id,
            'exhibition_description' => $this->title ?: 'Untitled',
            'status' => 'Approved',
        ]);
        $exhibitor->deleted_at = null;
        $exhibitor->save();
    }

    /**
     * Send the application back to limbo and take it out of the lineup.
     */
    public function deny(): void
    {
        $this->update(['approved' => false]);
        $this->exhibitor?->update(['status' => 'Denied']);
    }
}
