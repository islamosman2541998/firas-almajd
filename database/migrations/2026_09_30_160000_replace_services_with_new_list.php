<?php

use App\Support\SiteCache;
use Database\Seeders\ServiceSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /** Replaces the old services with the new service list (same images) on existing installs. */
    public function up(): void
    {
        (new ServiceSeeder)->run();

        SiteCache::flush();
    }

    public function down(): void
    {
        //
    }
};
