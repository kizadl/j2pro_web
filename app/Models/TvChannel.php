<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TvChannel extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'image_url',
        'source_type',
        'stream_url',
        'youtube_id',
        'watching_text',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(TvSchedule::class);
    }
}