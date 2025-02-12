<?php

namespace App\Models;

use Database\Factories\AssetRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetRequest extends Model
{
    /** @use HasFactory<AssetRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'category_id',
        'purpose',
        'quantity',
        'priority',       // enum: low, medium, high
        'status',         // enum: pending, approved, rejected, fulfilled
        'approved_by',
        'approval_date',
        'required_from',
        'required_until',
        'notes',
        'organization_id',
    ];

    protected $casts = [
        'approval_date' => 'datetime',
        'required_from' => 'datetime',
        'required_until'=> 'datetime',
    ];

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // The user that requested the asset.
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    // The user who approved the request.
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // The category of the asset being requested.
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }
}
