<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_OWNER = 'owner';
    public const ROLE_STAFF = 'staff';
    public const ROLE_KASIR = 'kasir';
    public const ROLE_PENAKSIR = 'penaksir';
    public const ROLE_USER = 'user';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_OWNER,
        self::ROLE_STAFF,
        self::ROLE_KASIR,
        self::ROLE_PENAKSIR,
        self::ROLE_USER,
    ];

    public const ADMINISTRATIVE_ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_OWNER,
        self::ROLE_STAFF,
        self::ROLE_KASIR,
        self::ROLE_PENAKSIR,
    ];

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Permissions assigned to the user for every menu entry.
     */
    public function menuPermissions(): HasMany
    {
        return $this->hasMany(UserMenuPermission::class);
    }

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, self::ADMINISTRATIVE_ROLES, true);
    }
}
