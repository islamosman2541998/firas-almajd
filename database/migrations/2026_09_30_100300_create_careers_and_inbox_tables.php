<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_jobs', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('employment_type')->nullable();
            $table->json('location')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_job_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email');
            $table->unsignedTinyInteger('experience')->default(0);
            $table->text('summary');
            $table->string('status', 20)->default('new')->index(); // new | reviewed | shortlisted | rejected
            $table->string('locale', 5)->default('ar');
            $table->ipAddress('ip')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->text('message');
            $table->string('status', 20)->default('new')->index(); // new | read | replied | archived
            $table->string('locale', 5)->default('ar');
            $table->ipAddress('ip')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('location', 20)->default('header')->index();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->json('title');
            $table->string('type', 20)->default('route'); // route | page | service | url | none
            $table->string('route_name', 60)->nullable();
            $table->nullableMorphs('linkable');
            $table->string('url')->nullable();
            $table->boolean('new_tab')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('career_jobs');
    }
};
