<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();

            // Descriptions (CMS)
            $table->text('short_description')->nullable(); // Résumé court
            $table->longText('description'); // Description complète (HTML via CMS)

            // Catégorie et médias
            $table->string('category');
            $table->string('image'); // Image principale (path)
            $table->json('gallery')->nullable(); // Galerie JSON: ["path1.jpg", "path2.jpg"]
            $table->string('video_url')->nullable();

            // Statuts et ordre
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('order')->default(0);

            // Compteurs (optionnel)
            $table->unsignedInteger('views')->default(0);

            $table->timestamps();

            // Index pour performances
            $table->index(['category', 'is_published']);
            $table->index('is_featured');
            $table->index(['order', 'created_at']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisations');
    }
};
