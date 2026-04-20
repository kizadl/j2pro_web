<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TvSchedule extends Model
{
    protected $fillable = [
        'tv_channel_id',
        'title',
        'description',
        'image_url',
        'day_index',
        'day_name',
        'start_time',
        'end_time',
        'start_minutes',
        'end_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(TvChannel::class, 'tv_channel_id');
    }
}