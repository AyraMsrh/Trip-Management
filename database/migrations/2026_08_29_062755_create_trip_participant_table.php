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
        Schema::create('trip_participant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('participant_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid',
            ])->default('pending');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();
            $table->unique([
                'trip_id',
                'participant_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_participant');
    }
};
