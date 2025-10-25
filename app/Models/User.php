<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'phone',
        'role',
        'is_active',
        'avatar_path',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'role' => UserRole::class,
        'password' => 'hashed',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function hasRole(UserRole|string $role): bool
    {
        $roleEnum = $role instanceof UserRole ? $role : UserRole::tryFrom($role);

        if (! $roleEnum || ! $this->role instanceof UserRole) {
            return false;
        }

        return $this->role === $roleEnum;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(UserRole::Admin);
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole(UserRole::Supervisor);
    }

    public function isAnalista(): bool
    {
        return $this->hasRole(UserRole::Analista);
    }

    public function isPromotor(): bool
    {
        return $this->hasRole(UserRole::Promotor);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->full_name;
    }

    public function setNombreCompletoAttribute(string $value): void
    {
        $this->attributes['full_name'] = $value;
    }

    public function getTelefonoAttribute(): ?string
    {
        return $this->phone;
    }

    public function setTelefonoAttribute(?string $value): void
    {
        $this->attributes['phone'] = $value;
    }

    public function getActivoAttribute(): bool
    {
        return (bool) $this->is_active;
    }

    public function setActivoAttribute($value): void
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'assignments')
            ->withPivot(['is_active', 'status', 'assigned_at', 'unassigned_at'])
            ->withTimestamps();
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function getFirstNameAttribute(): string
    {
        return explode(' ', $this->full_name, 2)[0];
    }
}
