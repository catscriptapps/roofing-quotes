<?php
// /src/Controller/AdvertsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Advert;
use App\Models\AdvertCallToAction;
use App\Models\Country; // Added
use App\Models\UserType; // Added
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;

class AdvertsController
{
    use RecentActivityLogger;

    /**
     * Prepare data for the My Adverts Page
     */
    public function index(): void
    {
        $query = $_GET['q'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        // 1. Get current user ID (Don't show other people's ads!)
        $userId = (int)($_SESSION['user_id'] ?? 0);

        // 2. Load BOTH user and cta relationships
        $builder = Advert::where('orig_user_id', $userId)->with(['owner', 'cta']);

        if (!empty($query)) {
            $builder->where(function ($q) use ($query) {
                $term = '%' . trim($query) . '%';
                // Using whereRaw to bypass any Eloquent character escaping issues
                $q->where('title', 'LIKE', $term)
                    ->orWhere('description', 'LIKE', $term)
                    ->orWhere('keywords', 'LIKE', $term);
            });
        }

        $totalFiltered = $builder->count();

        $ads = $builder->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        // AJAX response for search OR infinite scroll
        if (isset($_GET['page']) || isset($_GET['q'])) {
            header('Content-Type: application/json');

            $html = '';
            foreach ($ads as $ad) {
                $html .= self::renderCard($ad);
            }

            echo json_encode([
                'success' => true,
                'html'    => $html,
                'total'   => $totalFiltered,
                'hasMore' => ($offset + $ads->count()) < $totalFiltered
            ]);
            exit;
        }

        // Standard Page Load
        $html = '';
        foreach ($ads as $ad) {
            $html .= self::renderCard($ad);
        }

        // Fetch all available CTAs for the form dropdown
        $ctas = AdvertCallToAction::orderBy('call_to_action', 'asc')->get();

        // Pass them to the global scope for the JS init
        $GLOBALS['availableCtas'] = $ctas->toArray();
        $GLOBALS['advertCards'] = $html;
        $GLOBALS['title'] = "My Adverts";
        $GLOBALS['totalAdsCount'] = $totalFiltered;
    }

    /**
     * Handle Create or Update
     */
    public function save(array $data): array
    {
        try {
            $encodedId = $data['encoded_id'] ?? null;
            $isNew = empty($encodedId);

            $adId = !$isNew ? IdEncoder::decode($encodedId) : null;
            $ad = $adId ? Advert::find($adId) : new Advert();

            if (!$ad) throw new \Exception("Advert not found.");

            $ad->orig_user_id       = (int)($_SESSION['user_id'] ?? 1);
            $ad->title              = trim($data['title'] ?? '');
            $ad->description        = trim($data['description'] ?? '');
            $ad->call_to_action_id  = !empty($data['call_to_action_id']) ? (int)$data['call_to_action_id'] : null;
            $ad->keywords           = $data['keywords'] ?? null;
            $ad->landing_page_url   = $data['landing_page_url'] ?? null;

            /**
             * TARGETING REPAIR:
             * JS sends arrays. Model casts them. 
             * No need to json_decode unless the JS sends them as strings.
             */
            $ad->selected_countries  = $data['selected_countries'] ?? [];
            $ad->selected_user_types = $data['selected_user_types'] ?? [];

            $ad->advert_package = (int)($data['advert_package'] ?? 0);

            if ($isNew) {
                $ad->status = 'pending';
            }

            if (empty($ad->title)) throw new \Exception("The advert title is required.");

            $ad->save();

            $actionLabel = $isNew ? "Created new advert" : "Updated advert";
            static::logActivity("{$actionLabel}: {$ad->title}", 'Adverts');

            return [
                'success' => true,
                'data'    => $ad->toArray(),
                'cardHtml' => self::renderCard($ad),
                'messages' => ['Advert saved successfully.']
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Render individual Advert Card HTML
     */
    public static function renderCard(Advert $ad): string
    {
        $item = $ad->toArray();
        $item['cta_text'] = $ad->cta->call_to_action ?? 'Learn More';
        $item['encoded_id'] = IdEncoder::encode((int)$ad->advert_id);
        $item['advert_package'] = $ad->package->package_name . ' Package' ?? '';
        $item['advert_package_description'] = $ad->package->package_description ?? '';
        $item['advert_package_icon'] = $ad->package->package_icon ?? '';

        // Handle Countries display names
        $countries = $ad->selected_countries ?? [];
        if (in_array('ALL', (array)$countries)) {
            $item['country_names'] = ['ALL'];
        } else {
            $item['country_names'] = Country::whereIn('id', (array)$countries)
                ->pluck('country')
                ->toArray();
        }

        // Handle User Type display names
        $userTypes = $ad->selected_user_types ?? [];
        if (in_array('ALL', (array)$userTypes)) {
            $item['user_type_names'] = ['ALL'];
        } else {
            $item['user_type_names'] = UserType::whereIn('user_type_id', (array)$userTypes)
                ->pluck('user_type')
                ->toArray();
        }

        // Add formatted dates
        $item['created_at_formatted'] = $ad->created_at ? $ad->created_at->format('M d, Y') : 'N/A';
        $item['updated_at_formatted'] = $ad->updated_at ? $ad->updated_at->format('M d, Y') : 'N/A';

        // Mirroring UsersController: Set the global assetBase
        $GLOBALS['assetBase'] = getAssetBase();

        // Pass the owner object directly so the view can handle the avatar logic
        $owner = $ad->owner;
        $item['owner'] = $owner ? $owner->toArray() : null;
        $item['user_types_json'] = getUserRoles($owner);

        // Geography
        $item['owner_country'] = $owner->country->country ?? 'N/A';
        $item['owner_region']  = $owner->region->region ?? 'N/A';

        $path = __DIR__ . '/../../resources/views/components/adverts/data-card.php';

        ob_start();
        try {
            $assetBase = $GLOBALS['assetBase'] ?? $_ENV['APP_ASSET_BASE'] ?? '/';
            include $path;
        } catch (\Throwable $e) {
            ob_end_clean();
            return "<div class='p-4 border border-red-200 text-red-500'>Render Error: " . $e->getMessage() . "</div>";
        }
        return ob_get_clean();
    }

    /**
     * Handle Delete
     */
    public function delete($id): array
    {
        try {
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $ad = Advert::find($rawId);

            if ($ad) {
                $title = $ad->title;
                if ($ad->delete()) {
                    static::logActivity("Deleted advert: {$title}", 'Adverts');
                    return ['success' => true, 'messages' => ['Ad deleted successfully.']];
                }
            }
            return ['success' => false, 'messages' => ['Failed to delete advert.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }
}
