<?php

namespace App\Models;

use Database\Factories\AssetCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    /** @use HasFactory<AssetCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'depreciation_rate',
        'requires_maintenance',
        'maintenance_frequency',
    ];

    protected $casts = [
        'requires_maintenance' => 'boolean',
        'depreciation_rate'    => 'float',
    ];

    // Relationships

    // A category has many assets.
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'category_id');
    }

    // Self-referential relationship: category belongs to a parent category.
    public function parent(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'parent_id');
    }

    // A category can have many child categories.
    public function children(): HasMany
    {
        return $this->hasMany(AssetCategory::class, 'parent_id');
    }
}
