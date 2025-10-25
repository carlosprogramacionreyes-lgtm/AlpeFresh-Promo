<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'store_id',
        'chain_id',
        'assignment_id',
        'visited_at',
        'status',
        'latitude',
        'longitude',
        'geofence_valid',
        'availability',
        'quality_rating',
        'quality_observations',
        'quality_photo_path',
        'price_observed',
        'price_regular',
        'price_discount',
        'has_promotion',
        'price_photo_path',
        'incidents',
        'incident_comments',
        'review_notes',
        'submitted_at',
        'submitted_by',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'submitted_at' => 'datetime',
        'availability' => 'array',
        'incidents' => 'array',
        'geofence_valid' => 'boolean',
        'has_promotion' => 'boolean',
        'quality_rating' => 'integer',
        'price_observed' => 'float',
        'price_regular' => 'float',
        'price_discount' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $evaluation) {
            if (! $evaluation->code) {
                $evaluation->code = strtoupper(Str::ulid());
            }

            if (! $evaluation->status) {
                $evaluation->status = 'draft';
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function chain(): BelongsTo
    {
        return $this->belongsTo(Chain::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(EvaluationPhoto::class);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }
}
