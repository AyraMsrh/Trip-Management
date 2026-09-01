<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'passport_number',
        'email',
        'phone',
        'passport_path',
    ];

    public function trips(): BelongsToMany
    {
        return $this->belongsToMany(Trip::class, 'trip_participant')
            ->withPivot([
                'payment_status',
                'registered_at',
            ])
            ->withTimestamps();
    }
}