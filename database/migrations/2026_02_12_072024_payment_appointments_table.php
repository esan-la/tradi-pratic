<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Cette migration adapte la table payments existante pour supporter
     * les rendez-vous en ajoutant une colonne appointment_id nullable
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Ajouter la colonne appointment_id si elle n'existe pas
            if (!Schema::hasColumn('payments', 'appointment_id')) {
                $table->foreignId('appointment_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('appointments')
                      ->onDelete('cascade');
            }

            // Ajouter un index si nécessaire
            if (!Schema::hasColumn('payments', 'payable_type')) {
                // Si vous utilisez un polymorphic relationship
                $table->string('payable_type')->nullable()->after('appointment_id');
                $table->unsignedBigInteger('payable_id')->nullable()->after('payable_type');
                $table->index(['payable_type', 'payable_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'appointment_id')) {
                $table->dropForeign(['appointment_id']);
                $table->dropColumn('appointment_id');
            }

            if (Schema::hasColumn('payments', 'payable_type')) {
                $table->dropIndex(['payable_type', 'payable_id']);
                $table->dropColumn(['payable_type', 'payable_id']);
            }
        });
    }
};
