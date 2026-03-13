<?php
// /server/models/AdvertPic.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertPic extends Model
{
    protected $table = 'adverts_pics';
    protected $primaryKey = 'entry_id';

    public $incrementing = true;

    protected $fillable = [
        'advert_id',
        'pic_name',
        'pic_caption',
        'pos_index'
    ];

    protected $casts = [
        'entry_id'         => 'integer',
        'advert_id' => 'integer',
        'pos_index'        => 'integer',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /**
     * Relationship: The advert this picture belongs to
     */
    public function advert(): BelongsTo
    {
        return $this->belongsTo(Advert::class, 'advert_id', 'advert_id');
    }

    /**
     * Check if the picture belongs to an advert owned by the given user.
     */
    public function isOwnedBy(int $userId): bool
    {
        // Hop to the parent advert and check 'orig_user_id'
        return $this->advert && (int)$this->advert->orig_user_id === $userId;
    }
}
