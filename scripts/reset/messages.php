<?php
// /scripts/reset/messages.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Message;

function resetMessagesTable(): array
{
    $messages = [];

    try {
        $tableName = (new Message())->getTable();

        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('id');

            // Peer-to-Peer tracking 🤝
            $table->unsignedBigInteger('sender_id')->nullable()->index();
            $table->unsignedBigInteger('receiver_id')->nullable()->index();
            $table->string('conversation_id')->nullable()->index();

            // Contact info (Nullable for logged-in users, required for guests)
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();

            $table->string('subject')->index();
            $table->text('message');

            $table->boolean('is_read')->default(false)->index();
            $table->boolean('is_sent')->default(false)->index();
            $table->boolean('is_draft')->default(false)->index();
            $table->boolean('is_archived')->default(false)->index();

            $table->timestamps();
        });

        $messages[] = "{$tableName} table created successfully with peer-to-peer support";
    } catch (\Throwable $e) {
        $messages[] = "{$tableName} table error: " . $e->getMessage();
    }

    return $messages;
}
