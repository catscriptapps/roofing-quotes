<?php
// /server/models/Quote.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Eloquent Quote model for Completed Estimates
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
        'city',
        'country_id',
        'region_id',
        'postal_code',
        'access_code',
        'pdf_file_name',
        'status_id',
        'date_expires', // Added for expiry logic
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'date_expires' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
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
            default             => 'Unknown',
        };
    }

    /**
     * Determine whether this quote is expired.
     */
    public function isExpired(): bool
    {
        if ($this->date_expires === null) {
            return false;
        }

        return $this->date_expires->isPast();
    }

    // ============================================================
    // Query scopes
    // ============================================================

    /**
     * Scope: quotes that have expired.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('date_expires', '<', Carbon::now());
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