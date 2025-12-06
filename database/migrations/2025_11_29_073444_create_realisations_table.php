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
            $table->text('description');
            $table->string('category'); // Changé de enum à string pour plus de flexibilité
            $table->string('image'); // Image principale (obligatoire)
            $table->json('gallery')->nullable(); // Galerie d'images (optionnel)
            $table->string('video_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['category', 'is_published']);
            $table->index('is_featured');
            $table->index(['order', 'created_at']);
        });
    }

    // public function down(): void
    // {
    //     Schema::dropIfExists('realisations');
    // }
    public function down(): void
    {
        Schema::table('realisations', function (Blueprint $table) {
            $table->dropColumn(['image', 'gallery']);
            $table->json('images')->nullable();
            $table->dropIndex(['realisations_is_featured_index']);
            $table->dropIndex(['realisations_order_created_at_index']);
        });
    }
};
