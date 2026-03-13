<?php
// /server/models/Message.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'sender_id',    // The User ID of the person sending
        'receiver_id',  // The User ID of the Mentor receiving
        'conversation_id',
        'full_name',    // Kept for guest contact forms
        'email',        // Kept for guest contact forms
        'subject',
        'message',
        'is_read',
        'is_sent',
        'is_draft',
        'is_archived'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_sent' => 'boolean',
        'is_draft' => 'boolean',
        'is_archived' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship to the Sender
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relationship to the Receiver (Mentor)
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
