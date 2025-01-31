<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_leader_id')->constrained();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('accuracy')->nullable();
            $table->decimal('speed')->nullable();
            $table->integer('battery_level')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('tracked_at');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tracking_locations');
    }
};
