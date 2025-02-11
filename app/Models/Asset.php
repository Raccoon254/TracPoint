<?php

namespace App\Models;

use Database\Factories\AssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    /** @use HasFactory<AssetFactory> */
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'barcode',
        'name',
        'description',
        'category_id',
        'model',
        'manufacturer',
        'serial_number',
        'value',
        'purchase_date',
        'warranty_expiry',
        'status',                // enum: available, assigned, in_maintenance, retired
        'condition',             // enum: new, good, fair, poor
        'is_mobile',
        'current_department_id',
        'organization_id',
        'current_location',
        'assigned_to',
        'assigned_date',
        'expected_return_date',
        'depreciation_rate',
        'notes',
    ];

    protected $casts = [
        'purchase_date'       => 'date',
        'warranty_expiry'     => 'date',
        'assigned_date'       => 'date',
        'expected_return_date'=> 'date',
        'is_mobile'           => 'boolean',
    ];

    // Relationships

    // An asset belongs to a category.
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    // An asset belongs to a department (its current department).
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'current_department_id');
    }

    // An asset is assigned to a user.
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // An asset can have many asset images.
    public function assetImages(): HasMany
    {
        return $this->hasMany(AssetImage::class);
    }

    // An asset can have many audits.
    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }

    // An asset can have many maintenance records.
    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    // An asset belongs to an organization.
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
