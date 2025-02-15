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
        Schema::create('luggage_scans', function (Blueprint $table) {

            $table->id();
            $table->foreignId('luggage_id')->constrained()->onDelete('cascade');
            $table->foreignId('tour_leader_id')->constrained('tour_leaders')->onDelete('cascade'); // Foreign key for tour leader
            $table->dateTime('scanned_at');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luggage_scans');
    }
};
