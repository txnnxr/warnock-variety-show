<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'people';

    protected $fillable = [
        'name',
        'contact_info',
        'email',
        'phone_number',
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function exhibitors()
    {
        return $this->hasMany(Exhibitor::class);
    }
}
