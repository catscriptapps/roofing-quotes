<?php
// /scripts/reset/quotes.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Quote;

/**
 * Resets the quotes table.
 */
function resetQuotesTable(): array
{
    $messages = [];
    $tableName = (new Quote())->getTable();

    try {
        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('quote_id');

            // Core Identifiers
            $table->string('quote_number', 50)->unique()->index();
            $table->unsignedBigInteger('orig_user_id')->index();

            // Location Details
            $table->text('property_address');
            $table->unsignedInteger('country_id')->index();
            $table->unsignedInteger('region_id')->index();
            $table->string('postal_code', 20)->nullable();

            // Security & Assets
            $table->string('access_code', 100)->nullable();
            $table->string('pdf_file_name', 255)->nullable();

            // Status & Timestamps
            $table->unsignedInteger('status_id')->default(1)->index();
            $table->timestamps();

            // Foreign Key constraints
            $table->foreign('orig_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}