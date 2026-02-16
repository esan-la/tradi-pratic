<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =========================================================
        // TABLE: APPOINTMENTS
        // (Données spécifiques au client)
        // =========================================================
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            // Informations client
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('provenance');

            // Document d'identité
            $table->string('doctype')->nullable();
            $table->string('docnumber')->nullable();
            $table->string('imagedoc')->nullable();

            // Type de consultation
            $table->enum('consultation_type', [
                'traditional',
                'prayer',
                'natural_care',
                'Consultation_spirituelle',
                'Autres'
            ])->default('Autres');

            $table->string('autre_consultation')->nullable();
            $table->text('message')->nullable();

            // Statut
            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed'
            ])->default('pending');

            $table->text('admin_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('status');
            $table->index('phone');
            $table->index('email');
        });
        // Schema::create('appointments', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->nullable();
        //     $table->string('phone');
        //     $table->string('provenance');
        //     $table->string('doctype')->nullable();
        //     $table->string('docnumber')->nullable();
        //     $table->string('imagedoc')->nullable();
        //     $table->enum('consultation_type', [
        //         'traditional',
        //         'prayer',
        //         'natural_care',
        //         'Consultation_spirituelle',
        //         'Autres'
        //     ])->default('Autres');
        //     $table->string('autre_consultation')->nullable();
        //     $table->date('preferred_date');
        //     $table->time('preferred_time');
        //     $table->text('message')->nullable();
        //     $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
        //     $table->string('admin_notes')->nullable();
        //     $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
        //     $table->decimal('amount', 10, 2)->nullable();
        //     $table->string('code', 10)->nullable();
        //     $table->timestamp('confirmed_at')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->index(['preferred_date', 'status']);
        //     $table->index('phone');
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
