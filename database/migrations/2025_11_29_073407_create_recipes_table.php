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

            // Descriptions (CMS)
            $table->text('short_description')->nullable(); // Résumé court
            $table->longText('description'); // Description HTML via CMS

            // Données de la recette
            $table->json('ingredients'); // ["Ingrédient 1", "Ingrédient 2"]
            $table->json('instructions'); // ["Étape 1", "Étape 2"]

            // Temps et portions
            $table->integer('prep_time')->nullable(); // Minutes
            $table->integer('cook_time')->nullable(); // Minutes
            $table->integer('servings')->nullable(); // Nombre de personnes

            // Catégorie et médias
            $table->string('category')->nullable();
            $table->string('difficulty')->nullable(); // Facile, Moyen, Difficile
            $table->string('image')->nullable(); // Image principale
            $table->json('gallery')->nullable(); // Galerie photos
            $table->string('video_url')->nullable(); // YouTube/Vimeo

            // Statuts
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);

            // Compteurs
            $table->unsignedInteger('views')->default(0);

            $table->timestamps();

            // Index
            $table->index('slug');
            $table->index(['is_published', 'created_at']);
            $table->index('category');
            $table->index('views');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
