<?php

namespace App\Models;

use Database\Factories\AuditFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Audit extends Model
{
    /** @use HasFactory<AuditFactory> */
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'auditor_id',
        'audit_date',
        'previous_condition',
        'new_condition',
        'location_verified',
        'notes',
        'images',           // JSON array of image paths
        'discrepancies',
        'action_taken',
    ];

    protected $casts = [
        'audit_date' => 'datetime',
        'images'     => 'array',
    ];

    // Relationships

    // An audit belongs to an asset.
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    // An audit belongs to a user (auditor).
    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }
}
