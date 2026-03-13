<?php
// /server/models/MentorRequest.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorRequest extends Model
{
    // 💎 Ensure this matches your actual table name (mentors_requests)
    protected $table = 'mentors_requests';

    protected $fillable = [
        'sender_id',
        'mentor_id',
        'status',          // 'pending', 'accepted', 'declined'
        'status_id',       // Numeric status (1, 2, 3)
        'initial_message',
        'conversation_id', // 🎯 THE GOLDEN KEY: Must be fillable!
        'last_action_at'
    ];

    // Status constants for easy reference
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    /**
     * Relationship to the Mentor Card
     */
    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }
}
