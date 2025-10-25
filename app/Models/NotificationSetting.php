<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'event',
        'channel',
        'is_enabled',
        'conditions',
        'schedule',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'conditions' => 'array',
        'schedule' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
