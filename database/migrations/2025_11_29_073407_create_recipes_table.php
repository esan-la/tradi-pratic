<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('ingredients');           // JSON array
            $table->json('instructions');          // JSON array
            $table->integer('prep_time')->nullable();     // En minutes
            $table->integer('cook_time')->nullable();     // En minutes
            $table->integer('servings')->nullable();
            $table->string('category')->nullable();
            $table->string('image')->nullable();          // Changé de featured_image
            $table->string('video_url')->nullable();      // URL complète YouTube
            $table->boolean('is_published')->default(true);
            $table->integer('views_count')->default(0);
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('slug');
            $table->index(['is_published', 'created_at']);
            $table->index('views_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
