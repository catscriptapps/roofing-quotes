<?php
// /scripts/reset/faqs.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Faq;

function resetFaqsTable(): array
{
    $messages = [];

    try {
        $tableName = (new Faq())->getTable();

        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('status_id')->default(Faq::STATUS_ACTIVE)->index();
            $table->integer('display_order')->default(0)->index();
            $table->unsignedBigInteger('orig_user_id')->nullable()->index();
            $table->timestamps();

            // Foreign key to users table
            $table->foreign('orig_user_id')->references('id')->on('users')->onDelete('set null');
        });

        $messages[] = "created {$tableName} table";

        // --------------------
        // Default FAQ entries
        // --------------------
        $defaultFaqs = [

            [
                'question' => 'What is the Completed Estimates system used for?',
                'answer' => 'The Completed Estimates system is used by inspectors to upload completed roofing quote PDFs and securely share them with clients. Inspectors create a quote entry in the system, upload the PDF file, and generate an access code that allows the client to view the quote online.'
            ],

            [
                'question' => 'How do inspectors create a new quote entry?',
                'answer' => 'Inspectors can create a new quote by clicking the "Create Quote" button in the dashboard. The system will automatically generate a unique quote number (for example: DCU-QT25-0001). Once the quote entry is created, the inspector can upload the corresponding quote PDF file.'
            ],

            [
                'question' => 'How do I upload a quote PDF?',
                'answer' => 'After creating a quote entry, open the quote record and select the option to upload a file. Choose the completed roofing quote PDF from your computer and save the record. The uploaded PDF will then be stored in the system and associated with that quote number.'
            ],

            [
                'question' => 'How do clients access their roofing quote?',
                'answer' => 'Clients can access their quote from the home page of the Completed Estimates website. They simply enter the 6-digit access code provided by the inspector. Once the code is entered, the system will display the associated quote PDF for viewing or downloading.'
            ],

            [
                'question' => 'Who can see the quotes in the system?',
                'answer' => 'Inspectors can only see and manage the quotes that they personally create. Administrators have full access and can view, edit, or delete all quote records in the system.'
            ],

            [
                'question' => 'Can I edit or delete a quote?',
                'answer' => 'Yes. Inspectors can edit or delete the quotes they have created at any time unless the quote has been locked by an administrator. Administrators can manage all quotes in the system.'
            ],

        ];

        foreach ($defaultFaqs as $index => $faq) {
            Faq::create([
                'question'      => $faq['question'],
                'answer'        => $faq['answer'],
                'status_id'     => Faq::STATUS_ACTIVE,
                'display_order' => $index + 1,
                'orig_user_id'  => 1,
            ]);
        }

        $messages[] = "seeded " . count($defaultFaqs) . " Completed Estimates faqs with active status";

    } catch (\Throwable $e) {
        $messages[] = "Error resetting " . ($tableName ?? 'faqs') . " table: " . $e->getMessage();
    }

    return $messages;
}