<?php
// /src/Controller/MessagesController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Message;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Traits\RecentActivityLogger;
use Src\Service\AuthService;

class MessagesController
{
    use RecentActivityLogger;

    /**
     * The single source of truth for creating messages.
     * Handles both incoming Contact Form and outgoing Dashboard Compose.
     */
    public static function create(array $data): Message
    {
        $msg = new Message();

        if (!empty($data['is_sent'])) {
            $user = AuthService::currentUser();
            // Use a null-safe approach: check for object, then name, then fallback to 'Admin'
            $msg->full_name = ($user && isset($user->name)) ? $user->name : ($_SESSION['user_name'] ?? $_ENV['APP_NAME'] . ' Admin');
        } else {
            // For incoming contact forms
            $msg->full_name = $data['full_name'] ?? ($data['name'] ?? 'Anonymous');
        }

        // Ensure it's never null if both fallbacks fail
        $msg->full_name = $msg->full_name ?: 'System User';

        $msg->email     = $data['email'] ?? '';
        $msg->subject   = $data['subject'] ?? '';
        $msg->message   = $data['message'] ?? '';

        $msg->is_read     = (bool)($data['is_read'] ?? false);
        $msg->is_sent     = (bool)($data['is_sent'] ?? false);
        $msg->is_draft    = (bool)($data['is_draft'] ?? false);
        $msg->is_archived = (bool)($data['is_archived'] ?? false);

        $msg->save();

        $logAction = $msg->is_sent ? "Sent message to {$msg->email}" : 'Received contact message';
        static::logActivity($logAction, 'Message', $msg->id);

        return $msg;
    }

    /**
     * Renders a single table row for AJAX updates
     */
    public static function renderRow(Message $message): string
    {
        $item = $message;
        ob_start();
        include __DIR__ . '/../../resources/components/messages/table-row.php';
        return ob_get_clean();
    }

    /**
     * Paginate messages based on folder, excluding archived items from non-archive views
     */
    public static function paginate(string $folder = 'inbox', int $perPage = 50): LengthAwarePaginator
    {
        $query = Message::with('sender')->orderBy('created_at', 'desc');
        $currentUserId = $_SESSION['user_id'] ?? null;
        $isAdmin = AuthService::isAdmin();

        // 💎 SECURITY LAYER: If not admin, they can ONLY see their own messages
        if (!$isAdmin) {
            $query->where(function ($q) use ($currentUserId) {
                $q->where('sender_id', $currentUserId)
                    ->orWhere('receiver_id', $currentUserId);
            });
        }

        switch ($folder) {
            case 'handshakes':
                $query->where('conversation_id', 'LIKE', 'conv_%')
                    ->where('is_archived', false)
                    // 💎 THE FIX: Only show in 'Handshakes' folder if you are the RECEIVER.
                    // Senders find their requests in the 'Sent' folder.
                    ->where('receiver_id', $currentUserId);
                break;

            case 'archived':
                $query->where('is_archived', true);
                break;

            case 'sent':
                $query->where('is_sent', true)->where('is_archived', false);
                break;

            case 'unread':
                $query->where('is_read', false)->where('is_archived', false);
                break;

            default: // Inbox (Admin Only View for contact forms)
                if (!$isAdmin) {
                    // If a non-admin somehow hits 'inbox', force it to only show their handshakes
                    $query->where('conversation_id', 'LIKE', 'conv_%');
                } else {
                    $query->where('is_draft', false)
                        ->where('is_sent', false)
                        ->where('is_archived', false);
                }
                break;
        }

        return $query->paginate($perPage);
    }

    /**
     * Move a message to the archive
     */
    public static function archive(int $id): bool
    {
        $msg = Message::find($id);
        if (!$msg) return false;

        $msg->is_archived = true;
        $saved = $msg->save();

        if ($saved) {
            static::logActivity('Archived message', 'Message', $id);
        }

        return $saved;
    }

    public static function delete(int $id): bool
    {
        $msg = Message::find($id);
        if (!$msg) return false;

        $msg->delete();
        static::logActivity('Deleted message', 'Message', $id);
        return true;
    }

    public static function getForView(int $id): ?array
    {
        // Load the sender and their roles/types
        $msg = Message::with(['sender.roles'])->find($id);
        if (!$msg) return null;

        $currentUserId = (int)($_SESSION['user_id'] ?? 0);
        $isReceiver = (int)$msg->receiver_id === $currentUserId;
        $isSender = (int)$msg->sender_id === $currentUserId;

        // ... (Your existing read status logic) ...

        // 💎 PRIVACY & CONTEXT FIX:
        // Get roles from the sender object and map them to a simple array
        $userTypes = [];
        if ($msg->sender) {
            // If you use a roles relationship:
            $userTypes = $msg->sender->roles->pluck('name')->toArray();
            // Fallback if roles is just a string column:
            // $userTypes = explode(',', $msg->sender->user_type ?? 'Member');
        }

        // Handshake logic...
        $isHandshake = str_starts_with($msg->conversation_id ?? '', 'conv_');
        $handshakeId = null;
        $handshakeStatus = null;
        $canAction = false;

        if ($isHandshake) {
            $request = \App\Models\MentorRequest::where('conversation_id', $msg->conversation_id)->first();
            if ($request) {
                $handshakeId = $request->id;
                $handshakeStatus = $request->status;
                $canAction = $isReceiver && ($handshakeStatus === 'pending');
            }
        }

        return [
            'subject' => $msg->subject,
            'body'    => $msg->message,
            'name'    => $msg->sender->full_name ?? ($msg->full_name ?: 'System User'),
            'user_types' => $userTypes, // 🎯 Return array of strings like ['Mentor', 'Founder']
            'date'    => $msg->created_at->format('M d, Y g:i A'),
            'is_handshake' => $isHandshake,
            'handshake_id' => $handshakeId,
            'handshake_status' => $handshakeStatus,
            'can_action' => $canAction,
            'is_sender'  => $isSender
        ];
    }

    public static function markAsRead(int $id): bool
    {
        $msg = Message::find($id);
        if (!$msg) return false;

        $msg->is_read = true;
        $saved = $msg->save();

        if ($saved) {
            static::logActivity('Marked message as read', 'Message', $id);
        }

        return $saved;
    }

    /**
     * Returns the count of messages that are not read and not archived/drafts
     */
    public static function getUnreadCount(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $currentUserId = $_SESSION['user_id'] ?? null;
        if (!$currentUserId) return 0;

        $isAdmin = \Src\Service\AuthService::isAdmin();

        // Base criteria: Must be unread and not archived
        $query = Message::where('is_read', false)
            ->where('is_archived', false);

        if (!$isAdmin) {
            /**
             * 💎 REGULAR USER:
             * Count messages sent TO them, even if is_sent is 1 (Handshakes/Replies)
             */
            $query->where('receiver_id', (int)$currentUserId);
        } else {
            /**
             * 💎 ADMIN:
             * 1. General Inbox: (is_sent = 0 AND receiver_id is null/0)
             * 2. Their Handshakes: (receiver_id = currentUserId)
             */
            $query->where(function ($q) use ($currentUserId) {
                $q->where(function ($sub) {
                    $sub->where('is_sent', false)
                        ->where(function ($nullCheck) {
                            $nullCheck->whereNull('receiver_id')
                                ->orWhere('receiver_id', 0);
                        });
                })->orWhere('receiver_id', (int)$currentUserId);
            });
        }

        return (int)$query->count();
    }
}
