<?php
// /scripts/reset/users.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\User;

function resetUsersTable(): array
{
    $messages = [];

    try {
        $tableName = (new User())->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table";

        // 2. Recreate table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('first_name');
            $table->string('last_name');

            $table->string('email')->unique();

            $table->unsignedBigInteger('country_id')->nullable()->index();
            $table->unsignedBigInteger('region_id')->nullable()->index();

            $table->string('city')->nullable();

            $table->string('password');

            $table->unsignedInteger('status_id')->default(1)->index();

            $table->string('avatar_url')->nullable();

            $table->json('user_type_ids')->nullable();

            $table->boolean('email_verified')->default(false);

            $table->timestamp('user_last_log')->nullable();

            $table->timestamps();
        });

        $messages[] = "created {$tableName} table";

        // 3. Seed default users
        User::insert([
            [
                'first_name' => 'Cat',
                'last_name' => 'Nduanya',
                'email' => 'mindofcat@hotmail.com',
                'password' => password_hash('123xxx#A', PASSWORD_DEFAULT),
                'status_id' => 1,
                'user_type_ids' => json_encode([1, 2]),
                'email_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Joel',
                'last_name' => 'Davis',
                'email' => 'joel@unitedroofingbarrie.com',
                'password' => password_hash('123456#', PASSWORD_DEFAULT),
                'status_id' => 1,
                'user_type_ids' => json_encode([1, 2]),
                'email_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $messages[] = "seeded default users";

    } catch (\Throwable $e) {
        $messages[] = "{$tableName} table error: " . $e->getMessage();
    }

    return $messages;
}