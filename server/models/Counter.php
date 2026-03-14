<?php
// /server/models/Counter.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent Counter model to track sequential numbers (e.g., Invoices)
 */
class Counter extends Model
{
    protected $table = 'counters';
    protected $primaryKey = 'counter_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'type',      // 'invoice', 'task', etc.
        'year',      // '26', '27'
        'last_value' // The last used integer
    ];

    public $timestamps = true;
}
