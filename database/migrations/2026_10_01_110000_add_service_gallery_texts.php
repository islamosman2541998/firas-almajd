<?php

use Database\Seeders\UiTranslationSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /** Lists the new service gallery labels in the dashboard's site texts (existing edits are kept). */
    public function up(): void
    {
        (new UiTranslationSeeder)->run();
    }

    public function down(): void
    {
        //
    }
};
