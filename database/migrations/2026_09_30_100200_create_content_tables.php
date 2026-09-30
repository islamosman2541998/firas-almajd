<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('media_type', 10)->default('image'); // image | video
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->boolean('show_button')->default(true);
            $table->json('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->boolean('button_new_tab')->default(false);
            $table->string('button_bg', 20)->nullable();
            $table->string('button_color', 20)->nullable();
            $table->string('button_hover_bg', 20)->nullable();
            $table->unsignedTinyInteger('overlay_opacity')->default(100);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('short_description')->nullable();
            $table->string('image')->nullable();
            $table->json('scope_items')->nullable();
            $table->json('prep_items')->nullable();
            $table->boolean('show_on_home')->default(true);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_related', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('related_id')->constrained('services')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['service_id', 'related_id']);
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('location')->nullable();
            $table->json('category')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->string('image');
            $table->string('layout', 10)->default('normal'); // normal | wide | tall
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->string('image');
            $table->string('file')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->json('question');
            $table->json('answer');
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['faqs', 'partners', 'certificates', 'gallery_items', 'projects', 'service_related', 'services', 'sliders', 'pages'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
