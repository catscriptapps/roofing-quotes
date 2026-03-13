<?php
// /server/models/Advert.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Eloquent Advert model
 */
class Advert extends Model
{
    protected $table = 'adverts';
    protected $primaryKey = 'advert_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'orig_user_id',
        'title',
        'description',
        'call_to_action_id', // Changed from 'call_to_action' to use the FK
        'keywords',
        'landing_page_url',
        'selected_countries',
        'selected_user_types',
        'advert_package',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'selected_countries' => 'array',
        'selected_user_types' => 'array',
        'advert_package' => 'integer',
        'call_to_action_id' => 'integer',
        'expires_at' => 'datetime',
    ];

    // Package Constants
    const PACKAGE_FREE     = 0;
    const PACKAGE_1WEEK    = 1;
    const PACKAGE_1MONTH   = 2;
    const PACKAGE_6MONTHS  = 3;
    const PACKAGE_1YEAR    = 4;

    // ============================================================
    // Relationships
    // ============================================================

    /**
     * The CTA associated with this advert.
     */
    public function cta(): BelongsTo
    {
        return $this->belongsTo(AdvertCallToAction::class, 'call_to_action_id', 'call_to_action_id');
    }

    // --- AUTH & OWNERSHIP ---

    /**
     * Relationship: The user who created the quotation
     */
    public function owner(): BelongsTo
    {
        // This maps the 'orig_user_id' in quotations to 'id' in users
        return $this->belongsTo(User::class, 'orig_user_id', 'id');
    }

    /**
     * Helper to check if the ad is currently active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * The package tier associated with this advert.
     */
    public function package(): BelongsTo
    {
        // Local key: advert_package, Foreign key: package_id
        return $this->belongsTo(AdvertPackage::class, 'advert_package', 'package_id');
    }
}
