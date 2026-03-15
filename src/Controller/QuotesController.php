<?php
// /src/Controller/QuotesController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Quote;
use App\Models\Counter;
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;

class QuotesController
{
    use RecentActivityLogger;

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
        
        $builder = Quote::with([
            'owner.country', 
            'owner.region', 
            'country', 
            'region'
        ]);

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

        $quotes = $builder->orderBy('created_at', 'desc')->get();

        if (isset($_GET['q']) || (isset($_GET['limit']) && $_GET['limit'] === 'all')) {
            header('Content-Type: application/json');

            $data = isset($_GET['q'])
                ? array_map(fn($q) => ['rowHtml' => self::renderRow($q)], $quotes->all())
                : $quotes->all();

            echo json_encode([
                'success' => true,
                'data' => $data,
                'meta' => ['total' => Quote::count()]
            ]);
            exit;
        }

        $html = '';
        foreach ($quotes as $quote) {
            $html .= self::renderRow($quote);
        }

        $GLOBALS['quoteRows'] = $html;
        $GLOBALS['title'] = "Quotes";
        $GLOBALS['totalCount'] = $quotes->count();
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

    /**
     * Handle Create or Update
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

            $quote->property_address = trim($data['property_address'] ?? '');
            $quote->city             = trim($data['city'] ?? '');
            $quote->country_id       = (int)($data['country_id'] ?? 1);
            $quote->region_id        = (int)($data['region_id'] ?? 1);
            $quote->postal_code      = strtoupper(trim($data['postal_code'] ?? ''));
            $quote->access_code      = trim($data['access_code'] ?? '');
            $quote->pdf_file_name    = $data['pdf_file_name'] ?? $quote->pdf_file_name;
            $quote->status_id        = (int)($data['status_id'] ?? Quote::STATUS_DRAFT);

            if ($quote->save()) {
                if ($isNew) {
                    $this->incrementCounter('quote');
                }
            }

            $quote = $quote->fresh(['owner.country', 'owner.region', 'country', 'region']);

            $action = $isNew ? "Created" : "Updated";
            $logMsg = "{$action} Quote #{$quote->quote_number} for property in {$quote->city}";
            static::logActivity($logMsg, 'Quotes');

            return [
                'success'  => true,
                'quote_id' => $quote->quote_id,
                'rowHtml'  => self::renderRow($quote),
                'messages' => ["Quote #{$quote->quote_number} saved successfully."]
            ];

        } catch (\Throwable $e) {
            static::logActivity("Quote save failure: " . $e->getMessage(), 'Quotes');
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Specialized method for AJAX PDF Uploads from the Data Row
     */
    public function uploadPdf(array $data, array $files): array
    {
        try {
            $encodedId = $data['encoded_id'] ?? null;
            if (!$encodedId) throw new \Exception("Missing Quote ID.");
            
            $id = IdEncoder::decode($encodedId);
            $quote = Quote::find($id);
            if (!$quote) throw new \Exception("Quote not found.");

            if (!isset($files['quote_pdf']) || $files['quote_pdf']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception("Invalid file upload.");
            }

            $file = $files['quote_pdf'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            
            if (strtolower($ext) !== 'pdf') {
                throw new \Exception("Only PDF documents are allowed.");
            }

            // Clean filename: DCU-QT26-0001.pdf
            $newFileName = $quote->quote_number . '.pdf';
            $uploadDir = __DIR__ . '/../../public/uploads/quotes/';
            
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
                $quote->pdf_file_name = $newFileName;
                $quote->save();

                static::logActivity("Uploaded PDF for Quote #{$quote->quote_number}", 'Quotes');

                return [
                    'success' => true,
                    'rowHtml' => self::renderRow($quote->fresh(['owner', 'country', 'region'])),
                    'messages' => ["PDF uploaded successfully."]
                ];
            }

            throw new \Exception("Failed to save file to server.");

        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
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