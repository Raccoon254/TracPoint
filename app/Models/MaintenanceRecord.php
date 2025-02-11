<?php

namespace App\Models;

use Database\Factories\MaintenanceRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model
{
    /** @use HasFactory<MaintenanceRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'maintenance_type',      // enum: routine, repair, upgrade
        'performed_by',          // typically a user ID
        'maintenance_date',
        'cost',
        'description',
        'parts_replaced',
        'next_maintenance_date',
        'status',                // enum: scheduled, in_progress, completed
        'notes',
    ];

    protected $casts = [
        'maintenance_date'      => 'datetime',
        'next_maintenance_date' => 'datetime',
        'cost'                  => 'float',
    ];

    // Relationships

    // A maintenance record belongs to an asset.
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    // (Optional) The user who performed the maintenance.
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
