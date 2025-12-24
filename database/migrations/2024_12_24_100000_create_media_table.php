<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->unsignedBigInteger('size');
            $table->string('type')->default('image'); // image, video, document

            // Image specific
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();

            // Responsive variants (JSON: {mobile: path, tablet: path, desktop: path})
            $table->json('variants')->nullable();

            // AI Generated metadata
            $table->string('alt_text')->nullable();
            $table->text('description')->nullable();
            $table->string('tags')->nullable(); // comma separated
            $table->boolean('ai_generated')->default(false);

            // Organization
            $table->string('folder')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();

            $table->index(['type', 'folder']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
