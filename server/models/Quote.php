<?php
// /server/models/Quote.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent Quote model for Roofing Quotes
 */
class Quote extends Model
{
    protected $table = 'quotes';
    protected $primaryKey = 'quote_id';

    public $incrementing = true;
    protected $keyType = 'int';

    // Status Constants
    public const STATUS_DRAFT = 1;
    public const STATUS_POSTED = 2;

    protected $fillable = [
        'quote_number',
        'orig_user_id',
        'property_address',
        'country_id',
        'region_id',
        'postal_code',
        'access_code',
        'pdf_file_name',
        'status_id',
    ];

    public $timestamps = true;

    // ============================================================
    // Accessors & Helpers
    // ============================================================

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_id) {
            self::STATUS_DRAFT  => 'Draft',
            self::STATUS_POSTED => 'Posted',
            default            => 'Unknown',
        };
    }

    // ============================================================
    // Relationships
    // ============================================================

    /**
     * The user who generated/owns this quote.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'orig_user_id');
    }

    /**
     * Relationship to the Country lookup table.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Relationship to the Region lookup table.
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}