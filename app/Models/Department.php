<?php

namespace App\Models;

use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    /** @use HasFactory<DepartmentFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'parent_id',
        'location',
        'floor',
        'building',
        'manager_id',
    ];

    // Relationships
    // A department belongs to an organization.
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // A department has many users.
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'department_id');
    }

    // A department has many assets (currently assigned assets).
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'current_department_id');
    }

    // Self-referential relationship: department belongs to a parent department.
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    // A department can have many child departments.
    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    // The manager of the department (a user).
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
