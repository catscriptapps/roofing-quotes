<?php
// /scripts/reset/mentors-requests.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\MentorRequest;

/**
 * Resets the mentors_requests table with hand-shake logic 💎
 */
function resetMentorsRequestsTable(): array
{
    $messages = [];
    $tableName = (new MentorRequest())->getTable();

    try {
        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');

            // The user requesting help (Matches sender_id in messages)
            $table->unsignedBigInteger('sender_id')->index();

            // 🎯 THE MENTOR CARD ID (References the ID in the 'mentors' table, not 'users')
            $table->unsignedBigInteger('mentor_id')->index();

            // 💎 THE GOLDEN KEY: Unique thread ID to link Message <-> Request
            $table->string('conversation_id', 255)->nullable()->unique()->index();

            // Handshake State
            $table->string('status', 20)->default('pending'); // pending, accepted, declined
            $table->unsignedInteger('status_id')->default(1)->index();

            // Initial pitch message
            $table->text('initial_message')->nullable();

            // For tracking conversation flow
            $table->timestamp('last_action_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('sender_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            /** * NOTE: mentor_id here usually refers to the ID in the 'mentors' table.
             * If your DB supports it, link it to 'mentors', otherwise keep as index.
             */
        });

        $messages[] = "created {$tableName} table structure with Golden Key (conversation_id) support.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
