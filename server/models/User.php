<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * User Model
 * Table: users
 */
class User extends Model
{
    protected $table = 'users';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'country_id',
        'region_id',
        'city',
        'password',
        'status_id',
        'avatar_url',
        'user_type_ids',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'id'             => 'integer',
        'country_id'     => 'integer',
        'region_id'      => 'integer',
        'status_id'      => 'integer',
        'email_verified' => 'boolean',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'user_last_log'  => 'datetime',
        'user_type_ids'  => 'array',
    ];

    // ============================================================
    // Relationships
    // ============================================================

    /**
     * Country relationship.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Region relationship.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    // ============================================================
    // Accessors
    // ============================================================

    /**
     * Full name accessor.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    // ============================================================
    // User Types
    // ============================================================

    /**
     * Check if user has a specific type.
     *
     * Admin = 1
     * Inspector = 2
     */
    public function hasType(int $typeId): bool
    {
        return in_array($typeId, $this->user_type_ids ?? [], true);
    }
}