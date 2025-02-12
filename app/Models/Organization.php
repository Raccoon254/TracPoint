<?php

namespace App\Models;

use Database\Factories\OrganizationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    /** @use HasFactory<OrganizationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'logo',
        'website_url',
        //To store codes for user verification
        'verification_code',
    ];

    // An organization has many departments.
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    // Optionally, you can also define a direct relationship to users.
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // An organization has many asset categories.
    public function assetCategories(): HasMany
    {
        return $this->hasMany(AssetCategory::class);
    }

    // An organization has many assets.
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    // An organization has many asset requests.
    public function assetRequests(): HasMany
    {
        return $this->hasMany(AssetRequest::class);
    }
}
