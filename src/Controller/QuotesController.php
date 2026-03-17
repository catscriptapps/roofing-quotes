<?php
// /src/Controller/QuotesController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Quote;
use App\Models\Counter;
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;
use Src\Service\PdfUploadService;

class QuotesController
{
    use RecentActivityLogger;

    protected function getUploadService(): PdfUploadService
    {
        // Path updated to public/pdfs/quotes/
        $path = realpath(__DIR__ . '/../../public/pdfs/quotes/');
        if (!$path) {
            $path = __DIR__ . '/../../public/pdfs/quotes/';
        }
        return new PdfUploadService($path);
    }

    /**
     * Handle Create or Update from Modal
     */
    public function save(array $data): array
    {
        try {
            $encodedId = $data['encoded_id'] ?? null;
            $quoteId = !empty($encodedId) ? IdEncoder::decode($encodedId) : null;
            $isNew = !$quoteId;

            $quote = $quoteId ? Quote::find($quoteId) : new Quote();
            if (!$quote) throw new \Exception("Quote record not found.");

            if ($isNew) {
                $quote->quote_number = generateQuoteNumber();
                $quote->orig_user_id = (int)($_SESSION['user_id'] ?? 1);
            }

            /**
             * INTELLIGENT PDF HANDLING
             * If editing and no new file is in $_FILES, this block is skipped,
             * preserving the existing $quote->pdf_file_name.
             */
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                $service = $this->getUploadService();
                
                // 1. Capture the old filename if we are editing
                $oldFile = $quote->pdf_file_name;

                // 2. Upload the new file
                $newFile = $service->upload($_FILES['pdf_file']);
                
                if ($newFile) {
                    $quote->pdf_file_name = $newFile;

                    // 3. Cleanup: If there was an old file, delete it from the disk
                    if (!$isNew && !empty($oldFile)) {
                        $oldPath = realpath(__DIR__ . '/../../public/pdfs/quotes/') . DIRECTORY_SEPARATOR . $oldFile;
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                }
            }

            // Standard field mapping
            $quote->property_address = trim($data['property_address'] ?? '');
            $quote->city             = trim($data['city'] ?? '');
            $quote->country_id       = (int)($data['country_id'] ?? 1);
            $quote->region_id        = (int)($data['region_id'] ?? 1);
            $quote->postal_code      = strtoupper(trim($data['postal_code'] ?? ''));
            $quote->access_code      = trim($data['access_code'] ?? '');
            $quote->status_id        = (int)($data['status_id'] ?? Quote::STATUS_DRAFT);

            if ($quote->save()) {
                if ($isNew) $this->incrementCounter('quote');
            }

            // Refresh relations for a clean UI render
            $quote = $quote->fresh(['owner.country', 'owner.region', 'country', 'region']);
            static::logActivity(($isNew ? "Created" : "Updated") . " Quote #{$quote->quote_number}", 'Quotes');

            return [
                'success'  => true,
                'rowHtml'  => self::renderRow($quote),
                'messages' => ["Quote saved successfully."]
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Handle Quick AJAX PDF Uploads from Table Row
     */
    public function uploadPdf(array $data, array $files): array
    {
        try {
            $id = IdEncoder::decode($data['encoded_id'] ?? '');
            $quote = Quote::find($id);
            if (!$quote) throw new \Exception("Quote not found.");

            $service = $this->getUploadService();
            $fileName = $service->upload($files['quote_pdf']);

            if ($fileName) {
                $quote->pdf_file_name = $fileName;
                $quote->save();

                static::logActivity("Uploaded PDF for Quote #{$quote->quote_number}", 'Quotes');

                return [
                    'success' => true,
                    'rowHtml' => self::renderRow($quote->fresh(['owner', 'country', 'region'])),
                    'messages' => ["PDF uploaded successfully."]
                ];
            }
            throw new \Exception("Upload failed.");
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Handle Delete
     */
    public function delete($id): array
    {
        try {
            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $quote = Quote::find($rawId);

            if ($quote) {
                $qNum = $quote->quote_number;
                $address = $quote->property_address;
                $city = $quote->city;

                if ($quote->delete()) {
                    static::logActivity("Deleted Quote #{$qNum} for property at {$address}, {$city}", 'Quotes');
                    return ['success' => true, 'messages' => ['Quote deleted successfully.']];
                }
            }
            return ['success' => false, 'messages' => ['Quote not found or failed to delete.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Prepare data for the Quotes List Page or Search AJAX
     */
    public function index(): void
    {
        $query = $_GET['q'] ?? '';
        
        // Pagination logic
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 15; // Set your preferred items per page
        $offset = ($page - 1) * $perPage;

        $builder = Quote::with([
            'owner.country', 
            'owner.region', 
            'country', 
            'region'
        ]);

        // Search Filter
        if (!empty($query)) {
            $builder->where(function ($q) use ($query) {
                $term = '%' . trim($query) . '%';
                $q->where('quote_number', 'LIKE', $term)
                    ->orWhere('property_address', 'LIKE', $term)
                    ->orWhere('city', 'LIKE', $term)
                    ->orWhere('postal_code', 'LIKE', $term);
                
                $q->orWhereHas('owner', function ($rel) use ($term) {
                    $rel->where('first_name', 'LIKE', $term)
                        ->orWhere('last_name', 'LIKE', $term);
                });
            });
        }

        // Get total count for metadata before applying limit/offset
        $totalRecords = (clone $builder)->count();

        // Fetch paginated results
        $quotes = $builder->orderBy('created_at', 'desc')
                          ->offset($offset)
                          ->limit($perPage)
                          ->get();

        // Handle AJAX/API requests (Search or Infinite Scroll)
        if (isset($_GET['q']) || isset($_GET['page']) || (isset($_GET['limit']) && $_GET['limit'] === 'all')) {
            header('Content-Type: application/json');

            // Map rows to HTML if it's a search or scroll request
            $data = array_map(fn($q) => [
                'rowHtml' => self::renderRow($q),
                'encoded_id' => $q->encoded_id // Useful for JS side-checks
            ], $quotes->all());

            echo json_encode([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'total' => $totalRecords,
                    'currentPage' => $page,
                    'perPage' => $perPage,
                    'hasMore' => ($offset + $perPage) < $totalRecords
                ]
            ]);
            exit;
        }

        // Standard Page Load (PHP Rendering)
        $html = '';
        foreach ($quotes as $quote) {
            $html .= self::renderRow($quote);
        }

        // Global variables for the view
        $GLOBALS['quoteRows'] = $html;
        $GLOBALS['title'] = "Quotes";
        $GLOBALS['totalCount'] = $totalRecords;
    }

    /**
     * Render individual table row HTML
     */
    public static function renderRow(\App\Models\Quote $quote): string
    {
        $rowItem = $quote->toArray();

        $rowItem['encoded_id'] = IdEncoder::encode((int)$quote->quote_id);
        $rowItem['status_label'] = $quote->status_label;
        $rowItem['created_at_formatted'] = $quote->created_at ? $quote->created_at->format('M j, Y') : 'N/A';
        
        $GLOBALS['assetBase'] = getAssetBase();
        
        $owner = $quote->owner;
        $rowItem['owner'] = $owner ? $owner->toArray() : null;

        $rowItem['region_name'] = $quote->region->region ?? 'Ontario';
        $rowItem['country_name'] = $quote->country->country ?? 'Canada';
        
        $rowItem['owner_country'] = $owner->country->country ?? 'N/A';
        $rowItem['owner_region']  = $owner->region->region ?? 'N/A';
        $rowItem['user_types_json'] = getUserRoles($owner);

        // Check if PDF exists to determine button state in the row
        $rowItem['has_pdf'] = !empty($quote->pdf_file_name);

        $path = __DIR__ . '/../../resources/views/components/quotes/data-row.php';

        ob_start();
        try {
            if (file_exists($path)) {
                $assetBase = $GLOBALS['assetBase'];
                include $path;
            } else {
                throw new \Exception("Component not found: data-row.php");
            }
        } catch (\Throwable $e) {
            ob_end_clean();
            return "<tr><td colspan='6' class='p-4 text-red-500'>Render Error: " . $e->getMessage() . "</td></tr>";
        }
        return ob_get_clean();
    }

    private function incrementCounter(string $type): void
    {
        $year = date('y');
        Counter::updateOrCreate(
            ['type' => $type, 'year' => $year],
            ['last_value' => \Illuminate\Database\Capsule\Manager::raw('last_value + 1')]
        );
    }
}