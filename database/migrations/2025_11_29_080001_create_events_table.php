<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->enum('event_type', ['appointment', 'daily_work', 'meeting', 'other'])->default('other');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->foreignUuid('availability_period_id')
                ->nullable()
                ->constrained('availability_periods')
                ->onDelete('set null');
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'cancelled', 'completed'])->default('scheduled');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['start_datetime', 'end_datetime']);
            $table->index(['admin_id', 'status']);
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
