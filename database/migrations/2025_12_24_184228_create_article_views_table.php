<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Dzienne statystyki wyswietlen artykulow
        Schema::create('article_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->timestamps();

            $table->unique(['article_id', 'date']);
            $table->index('date');
        });

        // Sledzenie unikalnych odwiedzajacych (sesja hash - bez IP, zgodne z RODO)
        Schema::create('article_visitor_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->string('session_hash', 64); // SHA-256 hash sesji
            $table->date('date');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['article_id', 'session_hash', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_visitor_sessions');
        Schema::dropIfExists('article_views');
    }
};
