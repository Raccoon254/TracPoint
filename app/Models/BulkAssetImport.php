<?php

namespace App\Models;

use Database\Factories\BulkAssetImportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkAssetImport extends Model
{
    /** @use HasFactory<BulkAssetImportFactory> */
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'imported_by',
        'category_id',
        'department_id',
        'quantity',
        'status',         // enum: pending, processing, completed, failed
        'import_date',
        'notes',
    ];

    protected $casts = [
        'import_date' => 'datetime',
    ];

    // Relationships

    // The user who imported the batch.
    public function importer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    // The asset category for this bulk import.
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    // The department associated with this bulk import.
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // (Optional) If you wish to link imported assets to this bulk import,
    // ensure you have a 'bulk_asset_import_id' column in the assets table.
//    public function assets()
//    {
//        return $this->hasMany(Asset::class, 'bulk_asset_import_id');
//    }
}
