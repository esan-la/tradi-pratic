<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'appointment_id')) {
                $table->foreignUuid('appointment_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('appointments')
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('payments', 'payable_type')) {
                $table->string('payable_type')->nullable()->after('appointment_id');
                $table->uuid('payable_id')->nullable()->after('payable_type');
                $table->index(['payable_type', 'payable_id']);
            }
        });
    }

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
