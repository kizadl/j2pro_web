<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeMenu extends Model
{
    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'status',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}