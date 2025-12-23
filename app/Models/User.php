<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_TECHNICIAN = 'technik';
    public const ROLE_EDITOR = 'redaktor';
    public const ROLE_DOCTOR = 'lekarz';
    public const ROLE_ASSISTANT = 'asystent';

    public const ROLES = [
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_TECHNICIAN => 'Technik',
        self::ROLE_EDITOR => 'Redaktor',
        self::ROLE_DOCTOR => 'Lekarz',
        self::ROLE_ASSISTANT => 'Asystent',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isTechnician(): bool
    {
        return $this->role === self::ROLE_TECHNICIAN;
    }

    public function isEditor(): bool
    {
        return $this->role === self::ROLE_EDITOR;
    }

    public function isDoctor(): bool
    {
        return $this->role === self::ROLE_DOCTOR;
    }

    public function isAssistant(): bool
    {
        return $this->role === self::ROLE_ASSISTANT;
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canManageBlog(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_TECHNICIAN,
            self::ROLE_EDITOR,
        ], true);
    }

    public function canManageAppointments(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_TECHNICIAN,
            self::ROLE_DOCTOR,
            self::ROLE_ASSISTANT,
        ], true);
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->byRole(self::ROLE_ADMIN);
    }

    public function scopeDoctors(Builder $query): Builder
    {
        return $query->byRole(self::ROLE_DOCTOR);
    }

    public function scopeEditors(Builder $query): Builder
    {
        return $query->byRole(self::ROLE_EDITOR);
    }
}
