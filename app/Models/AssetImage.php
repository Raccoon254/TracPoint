<?php

namespace App\Models;

use Database\Factories\AssetImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetImage extends Model
{
    /** @use HasFactory<AssetImageFactory> */
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'image_path',
        'image_type',    // enum: primary, additional
        'description',
    ];

    // Relationships

    // An asset image belongs to an asset.
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
