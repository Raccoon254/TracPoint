<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',           // enum: super_admin, admin, auditor, user
        'organization_id',
        'department_id',
        'position',
        'employee_id',
        'phone',
        'status',         // enum: active, inactive
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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

    // A user belongs to an organization.
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // A user belongs to a department.
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // A user can have many asset requests (as the requester).
    public function assetRequests(): HasMany
    {
        return $this->hasMany(AssetRequest::class, 'requester_id');
    }

    // A user can be assigned many assets.
    public function assignedAssets(): HasMany
    {
        return $this->hasMany(Asset::class, 'assigned_to');
    }

    // A user can have many audits (as the auditor).
    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class, 'auditor_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAuditor(): bool
    {
        return $this->role === 'auditor';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }
}
