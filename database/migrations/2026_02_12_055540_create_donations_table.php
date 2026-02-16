<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['money', 'cheque', 'object', 'package']);
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 10)->default('XOF');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'received', 'cancelled'])->default('pending');
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
