<?php
// /src/Controller/MentorsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Mentor;
use App\Models\MentorRequest;
use App\Models\UserType;
use App\Models\Country;
use App\Models\Message;
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;
use Illuminate\Database\Capsule\Manager as Capsule;

class MentorsController
{
    use RecentActivityLogger;

    /**
     * Handle Delete for a Mentor Profile 🗑️
     */
    public function delete($id): array
    {
        try {
            $userId = (int)($_SESSION['user_id'] ?? 0);
            if ($userId === 0) throw new \Exception("Unauthorized.");

            // Decode if it's an encoded string, otherwise cast to int
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;

            $mentor = Mentor::find($rawId);

            if ($mentor) {
                // Security Check: Ensure the user owns this mentor card 🛡️
                if ((int)$mentor->orig_user_id !== $userId) {
                    throw new \Exception("You do not have permission to delete this profile.");
                }

                $headline = $mentor->headline;

                // Handle associated data if necessary 
                // (e.g., if you have a MentorReview model or similar)
                if (method_exists($mentor, 'reviews')) {
                    $mentor->reviews()->delete();
                }

                if ($mentor->delete()) {
                    // Log the activity using the headline for clarity
                    static::logActivity("Deleted mentor specialty: {$headline}", 'Mentors');

                    return [
                        'success' => true,
                        'messages' => ["Expertise '{$headline}' removed successfully."]
                    ];
                }
            }

            return ['success' => false, 'messages' => ['Failed to locate mentor profile for deletion.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Prepare data for the Expert Network / Mentors Page
     */
    public function index(): void
    {
        $query = $_GET['q'] ?? '';
        $targetType = (int)($_GET['target_type'] ?? 0);
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $builder = Mentor::with(['user', 'targetUserType', 'country', 'region'])
            ->where('is_active', true);

        // Filter by Search Term (Headline, Bio, Skills, City, Country, or Region) 💎
        if (!empty($query)) {
            $builder->where(function ($q) use ($query) {
                $term = '%' . trim($query) . '%';
                $q->where('headline', 'LIKE', $term)
                    ->orWhere('bio', 'LIKE', $term)
                    ->orWhere('skills', 'LIKE', $term)
                    ->orWhere('city', 'LIKE', $term)
                    // Deep search into Country table 🌍
                    ->orWhereHas('country', function ($sq) use ($term) {
                        $sq->where('country', 'LIKE', $term);
                    })
                    // Deep search into Region table 📍
                    ->orWhereHas('region', function ($sq) use ($term) {
                        $sq->where('region', 'LIKE', $term);
                    });
            });
        }

        // Filter by target audience (Mentees)
        if ($targetType > 0) {
            $builder->where('target_user_type_id', $targetType);
        }

        $totalFiltered = $builder->count();
        $mentors = $builder->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        // Standard logic for AJAX/Page Load remains the same...
        $html = '';
        foreach ($mentors as $mentor) {
            $html .= self::renderCard($mentor);
        }

        if (isset($_GET['page']) || isset($_GET['q']) || isset($_GET['target_type'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'html'    => $html,
                'total'   => $totalFiltered,
                'hasMore' => ($offset + $mentors->count()) < $totalFiltered
            ]);
            exit;
        }

        $GLOBALS['mentorCards'] = $html;
        $GLOBALS['userTypes']   = UserType::orderBy('user_type', 'asc')->get()->toArray();
        $GLOBALS['countries']   = Country::orderBy('country', 'asc')->get()->toArray();
        $GLOBALS['totalMentors'] = $totalFiltered;
        $GLOBALS['title']        = "Expert Network";
    }

    /**
     * Handle Create or Update for a Mentor Profile
     */
    public function save(array $data): array
    {
        try {
            $encodedId = $data['encoded_id'] ?? null;
            $isNew = empty($encodedId);
            $userId = (int)($_SESSION['user_id'] ?? 0);

            if ($userId === 0) throw new \Exception("Unauthorized.");

            $id = !$isNew ? IdEncoder::decode($encodedId) : null;

            // Logic: Find by ID, or find existing for user, or create new
            $mentor = $id ? Mentor::find($id) : new Mentor();

            $mentor->orig_user_id        = $userId;
            $mentor->target_user_type_id = (int)($data['target_user_type_id'] ?? 0);
            $mentor->country_id          = (int)($data['country_id'] ?? 1);
            $mentor->region_id           = (int)($data['region_id'] ?? 0);
            $mentor->city                = trim($data['city'] ?? ''); // Added 💎
            $mentor->headline            = trim($data['headline'] ?? '');
            $mentor->bio                 = trim($data['bio'] ?? '');
            $mentor->years_experience    = (int)($data['years_experience'] ?? 0); // Added 💎
            $mentor->youtube_url         = trim($data['youtube_url'] ?? ''); // Added 💎
            $mentor->website_url         = trim($data['website_url'] ?? ''); // Added 💎
            $mentor->is_active           = isset($data['is_active']) ? (bool)$data['is_active'] : true;

            // Handle Skills Tags
            if (isset($data['skills'])) {
                // If it's a comma-separated string from the form, convert to array for Eloquent JSON casting
                $skillsArray = is_array($data['skills']) ? $data['skills'] : explode(',', $data['skills']);
                $mentor->skills = array_map('trim', $skillsArray);
            }

            $mentor->save();

            $actionLabel = $isNew ? "Registered as a mentor" : "Updated mentor profile";
            static::logActivity("{$actionLabel}: {$mentor->headline}", 'Mentors');

            return [
                'success'  => true,
                'cardHtml' => self::renderCard($mentor->fresh(['user', 'targetUserType', 'country', 'region'])),
                'messages' => ['Mentor profile saved successfully.']
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Handle Connection Requests
     */
    public function sendRequest(array $data): array
    {
        // Check if user is logged in
        $currentUserId = $_SESSION['user_id'] ?? null;

        if (!$currentUserId) {
            return ['success' => false, 'message' => 'You must be logged in to connect with mentors.', 'status' => 401];
        }

        try {
            $mentorId = isset($data['mentor_id']) ? (int)$data['mentor_id'] : null;
            $initialPitch = $data['message'] ?? '';
            $receiverId = $data['receiver_id'] ?? 0;

            if (!$mentorId || empty($initialPitch)) {
                return ['success' => false, 'message' => 'Mentor ID and a message are required.', 'status' => 400];
            }

            // 💎 Force integer comparison to prevent type-juggling bypass
            if ((int)$currentUserId === (int)$mentorId) {
                return ['success' => false, 'message' => 'You cannot request a connection with yourself.', 'status' => 400];
            }

            // Check for existing request to prevent spam
            $existing = MentorRequest::where('sender_id', $currentUserId)->where('mentor_id', $mentorId)->first();

            if ($existing) {
                $msg = ($existing->status === 'pending')
                    ? 'You already have a pending request with this mentor.'
                    : 'A connection with this mentor already exists.';
                return ['success' => false, 'message' => $msg, 'status' => 400];
            }

            // 💎 GENERATE UNIQUE CONVERSATION ID ONCE
            $conversationId = 'conv_' . bin2hex(random_bytes(8));

            // 3. Atomic Transaction: Create Request + First Message
            $result = Capsule::transaction(function () use ($currentUserId, $mentorId, $initialPitch, $receiverId, $conversationId) {

                // Create the Handshake
                $request = MentorRequest::create([
                    'sender_id'       => $currentUserId,
                    'mentor_id'       => $mentorId,
                    'status'          => MentorRequest::STATUS_PENDING,
                    'status_id'       => 1,
                    'initial_message' => $initialPitch,
                    'conversation_id' => $conversationId, // 🎯 Link 1
                    'last_action_at'  => date('Y-m-d H:i:s')
                ]);

                // Create the actual Message record
                $message = Message::create([
                    'sender_id'       => $currentUserId,
                    'receiver_id'     => $receiverId,
                    'mentor_id'       => $mentorId,        // 🎯 Specific Identifier
                    'conversation_id' => $conversationId, // 🎯 Link 2
                    'subject'         => 'New Mentor Connection Request',
                    'message'         => $initialPitch,
                    'is_read'         => false,
                    'is_sent'         => true
                ]);

                return ['request' => $request, 'message' => $message, 'status' => 200];
            });

            return [
                'success' => true,
                'message' => 'Your request has been sent! The mentor will review it shortly.',
                'data'    => $result
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Server Error: ' . $e->getMessage(), 'status' => 500];
        }
    }

    /**
     * Render individual Mentor Card
     */
    public static function renderCard(Mentor $mentor): string
    {
        $item = $mentor->toArray();
        $item['encoded_id'] = IdEncoder::encode((int)$mentor->id);

        // Mirroring UsersController: Set the global assetBase
        $GLOBALS['assetBase'] = getAssetBase();

        // 1. Handle Owner (The User profile)
        $owner = $mentor->user;
        $item['owner'] = $owner ? $owner->toArray() : null;

        // 2. Map Mentor Location (Specific to this Mentor Post) 💎
        // This pulls from the country() and region() relationships in Mentor.php
        $item['country_name'] = $mentor->country->country ?? 'N/A';
        $item['region_name']  = $mentor->region->region ?? 'N/A';

        // 3. Map Owner Geography (The User's home location)
        // Accessing the relationships via the User model
        $item['owner_country'] = $owner->country->country ?? 'N/A';
        $item['owner_region']  = $owner->region->region ?? 'N/A';

        // 4. Roles Mapping
        $item['user_types_json'] = getUserRoles($owner);

        // 5. Target Audience Mapping
        $item['target_user_type'] = $mentor->targetUserType ? $mentor->targetUserType->toArray() : null;

        $path = __DIR__ . '/../../resources/views/components/mentors/data-card.php';

        ob_start();
        try {
            $assetBase = $GLOBALS['assetBase'];
            include $path;
        } catch (\Throwable $e) {
            ob_end_clean();

            return "<div class='p-4 text-red-500 font-bold'>Render Error: " . $e->getMessage() . "</div>";
        }
        return ob_get_clean();
    }

    /**
     * Handle Accept/Decline for a Mentorship Request
     */
    public function handleRequestAction(array $data): array
    {
        $currentUserId = $_SESSION['user_id'] ?? null;
        if (!$currentUserId) return ['success' => false, 'message' => 'Unauthorized'];

        try {
            $requestId = (int)($data['request_id'] ?? 0);
            $action = $data['action'] ?? ''; // 'accepted' or 'declined'

            // 1. Find the Handshake Record
            $request = \App\Models\MentorRequest::find($requestId);
            if (!$request) throw new \Exception("Request not found.");

            // 2. 💎 MANUAL VERIFICATION: Resolve the Mentor Card to check ownership
            // We find the Mentor Card (e.g., 6) and check if User 3 owns it
            $mentorCard = \App\Models\Mentor::find($request->mentor_id);

            if (!$mentorCard || (int)$mentorCard->orig_user_id !== (int)$currentUserId) {
                throw new \Exception("Unauthorized: You do not own this mentor profile.");
            }

            // 3. Update the Handshake Status
            $request->status = ($action === 'accepted') ? 'accepted' : 'declined';
            $request->save();

            // 4. Send a Notification Message back to the Sender (User 1)
            // This keeps the Gonachi inbox thread alive
            \Src\Controller\MessagesController::create([
                'sender_id'       => $currentUserId,
                'receiver_id'     => $request->sender_id,
                'subject'         => "Handshake Update: " . ucfirst($action),
                'message'         => "Your mentorship request has been " . ($action === 'accepted' ? 'accepted!' : 'declined.'),
                'is_sent'         => true,
                'is_read'         => false,
                'conversation_id' => 'handshake_reply_' . $request->id
            ]);

            return ['success' => true, 'message' => "Request {$action} successfully."];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
