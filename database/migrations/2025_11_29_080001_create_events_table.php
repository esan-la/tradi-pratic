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
        // =========================================================
        // TABLE: EVENTS
        // (Occupation réelle du calendrier)
        // =========================================================
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');

            $table->string('title');

            // Type d'événement
            $table->enum('event_type', [
                'appointment',
                'daily_work',
                'meeting',
                'other'
            ])->default('other');

            // Dates et heures
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');

            // Lien avec la disponibilité (optionnel)
            $table->foreignId('availability_period_id')
                  ->nullable()
                  ->constrained('availability_periods')
                  ->onDelete('set null');

            $table->text('description')->nullable();

            // Statut
            $table->enum('status', [
                'scheduled',
                'cancelled',
                'completed'
            ])->default('scheduled');

            $table->timestamps();
            $table->softDeletes();

            // Index pour recherche rapide
            $table->index(['start_datetime', 'end_datetime']);
            $table->index(['admin_id', 'status']);
            $table->index('event_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
