<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'category', // 'hiking', 'sports', 'design', 'hangout', etc.
        'image_url',
        'location_name',
        'city',
        'start_time',
        'max_participants',
        'status', // 'upcoming', 'ongoing', 'completed', 'cancelled'
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'max_participants' => 'integer',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }
}
