\<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

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
}
