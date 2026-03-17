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
     * Handle Delete
     */
    public function delete($id): array
    {
        try {
            // Handle cases where ID comes from the body (like your factory sends it)
            if (is_array($id)) {
                $encodedId = $id['id'] ?? $id['encoded_id'] ?? null;
                $rawId = IdEncoder::decode($encodedId);
            } else {
                $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            }

            $quote = Quote::find($rawId);
            if (!$quote) {
                return ['success' => false, 'messages' => ['Quote not found for deletion.']];
            }

            $qNum = $quote->quote_number;
            $pdfName = $quote->pdf_file_name;

            // --- THE FIX: RELIABLE PATHING ---
            if (!empty($pdfName)) {
                // DOCUMENT_ROOT is the /public folder in most setups
                $path = $_SERVER['DOCUMENT_ROOT'] . '/pdfs/quotes/' . $pdfName;
                
                if (file_exists($path)) {
                    unlink($path);
                } else {
                    // Manual fallback check
                    $fallback = __DIR__ . '/../../public/pdfs/quotes/' . $pdfName;
                    if (file_exists($fallback)) {
                        unlink($fallback);
                    }
                }
            }

            if ($quote->delete()) {
                static::logActivity("Deleted Quote #{$qNum}", 'Quotes');
                return ['success' => true, 'messages' => ["Quote #{$qNum} deleted successfully."]];
            }

            return ['success' => false, 'messages' => ['Failed to remove quote from database.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Handle Create or Update from Modal
     */
    public function save(array $data): array
    {
        // 1. ROUTING GUARD: If a DELETE request accidentally hits this method
        if (isset($data['_method']) && $data['_method'] === 'DELETE') {
            return $this->delete($data);
        }

        try {
            $encodedId = $data['encoded_id'] ?? null;
            $quoteId = !empty($encodedId) ? IdEncoder::decode($encodedId) : null;
            $isNew = !$quoteId;

            $quote = $quoteId ? Quote::find($quoteId) : new Quote();
            if (!$quote) throw new \Exception("Quote record not found.");

            // 2. REQUIRED FOR NEW QUOTES: Quote Number & User ID
            if ($isNew) {
                $quote->quote_number = generateQuoteNumber();
                // Ensure we have a valid user ID for the foreign key constraint
                $quote->orig_user_id = (int)($_SESSION['user_id'] ?? 1); 
            }

            // 3. MAP ALL FIELDS (Restoring missing Address and City)
            $quote->property_address = trim($data['property_address'] ?? '');
            $quote->city             = trim($data['city'] ?? '');
            $quote->country_id       = (int)($data['country_id'] ?? 1);
            $quote->region_id        = (int)($data['region_id'] ?? 1);
            $quote->postal_code      = strtoupper(trim($data['postal_code'] ?? ''));
            $quote->access_code      = trim($data['access_code'] ?? '');
            $quote->status_id        = (int)($data['status_id'] ?? Quote::STATUS_DRAFT);

            // 4. PDF HANDLING
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                $service = $this->getUploadService();
                
                // Cleanup old file if editing
                if (!$isNew && !empty($quote->pdf_file_name)) {
                    $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/pdfs/quotes/' . $quote->pdf_file_name;
                    if (file_exists($oldPath)) { @unlink($oldPath); }
                }

                // Upload using the EXACT quote number in CAPS
                $newFile = $service->upload($_FILES['pdf_file'], $quote->quote_number);
                if ($newFile) {
                    $quote->pdf_file_name = $newFile;
                }
            }

            // 5. SAVE TO DATABASE
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
            // This will now catch things like the SQL error and show you why
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
            $oldFile = $quote->pdf_file_name;

            // Delete previous file if exists
            if (!empty($oldFile)) {
                $oldPath = realpath(__DIR__ . '/../../public/pdfs/quotes/') . DIRECTORY_SEPARATOR . $oldFile;
                if (file_exists($oldPath)) { @unlink($oldPath); }
            }

            // Explicitly pass the quote number for naming
            $fileName = $service->upload($files['quote_pdf'], $quote->quote_number);

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