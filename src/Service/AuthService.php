<?php

declare(strict_types=1);

namespace Src\Service;

use App\Models\User;

/**
 * Class AuthService
 * Centralized authentication service updated for the modernized users table.
 */
class AuthService
{
    /**
     * Retrieves the currently logged-in user from the database.
     * Uses the standard 'id' primary key.
     */
    public static function currentUser(): ?User
    {
        self::ensureSession();

        return isset($_SESSION['user_id'])
            ? User::find((int)$_SESSION['user_id'])
            : null;
    }

    /**
     * Check if the user has access to a specific app.
     * In Gonachi, 'Users' is restricted to Admins (Type 1) only.
     */
    public static function hasAccess(string $appName): bool
    {
        if (self::isAdmin()) {
            return true;
        }

        // If the app is 'Users', and they aren't an Admin, it's a hard NO.
        if (strtolower($appName) === 'users') {
            return false;
        }

        // For other apps (Dashboard, Feed, etc.), we can check for a logged-in session
        $user = self::currentUser();
        return $user !== null;
    }

    /**
     * Ensures that a PHP session is started.
     */
    protected static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Determine if the current user is an admin.
     * Admin is defined as having user_type_id 1 in their collection.
     */
    public static function isAdmin(): bool
    {
        self::ensureSession();
        $uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

        if ($uid === 0) {
            return false;
        }

        // Fetch the user to check their modernized type collection
        $user = \App\Models\User::find($uid);

        if (!$user || !is_array($user->user_type_ids)) {
            return false;
        }

        // Check if 1 (Admin) exists in their array of types
        return in_array(1, $user->user_type_ids);
    }

    /**
     * Determine if the current user is Cat (ID 1).
     */
    public static function isCat(): bool
    {
        self::ensureSession();
        return isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === 1;
    }

    /**
     * Checks if a user is currently logged in.
     */
    public static function isLoggedIn(): bool
    {
        self::ensureSession();
        return isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0;
    }

    /**
     * Attempt to authenticate a user.
     * Maps the database 'id' to the session 'user_id'.
     */
    public static function login(string $email, string $password): bool
    {
        self::ensureSession();

        $user = User::where('email', $email)->first();

        if ($user && password_verify($password, $user->password)) {
            // We store the standard id in the session
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_full_name'] = $user->full_name;

            // Update legacy log timestamp
            $user->user_last_log = date('Y-m-d H:i:s');
            $user->save();

            return true;
        }

        return false;
    }

    /**
     * Logs out the current user.
     */
    public static function logout(): void
    {
        self::ensureSession();
        session_unset();
        session_destroy();
    }

    /**
     * Get the current user ID.
     */
    public static function userId(): ?int
    {
        self::ensureSession();
        return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    }
}
