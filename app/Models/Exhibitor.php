<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exhibitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'person_id',
        'show_id',
        'exhibition_description',
        'status',
        'performance_order',
        'plus_one',
    ];

    protected $casts = [
        'plus_one' => 'boolean',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function show()
    {
        return $this->belongsTo(Show::class);
    }
}
