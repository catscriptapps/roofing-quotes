<?php
// /scripts/reset/counters.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Counter;

/**
 * Resets the counters table by dropping and recreating it.
 */
function resetCountersTable(): array
{
    $messages = [];

    try {
        $tableName = (new Counter())->getTable();

        // Drop existing table if it exists
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table";

        // Create table
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('counter_id');
            $table->string('type');
            $table->char('year', 2);
            $table->unsignedInteger('last_value')->default(0);
            $table->timestamps();

            // Crucial: A single year can only have one counter per type
            $table->unique(['type', 'year']);

            // Optimization index
            $table->index(['type', 'year']);
        });
        $messages[] = "created {$tableName} table";
    } catch (\Throwable $e) {
        $messages[] = 'Counters table error: ' . $e->getMessage();
    }

    return $messages;
}
