<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->string('type', 10)->default('image')->after('title'); // image | video
            $table->string('video')->nullable()->after('image');
            $table->string('image')->nullable()->change(); // optional cover for videos
        });
    }

    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn(['type', 'video']);
        });
    }
};
