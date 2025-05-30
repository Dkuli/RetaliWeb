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
        Schema::create('luggage', function (Blueprint $table) {
                $table->id();
                $table->string('luggage_number')->unique(); // Nomor koper yang unik
                $table->string('pilgrim_name'); // Nama jamaah
                $table->string('phone')->nullable(); // Nomor telepon
                $table->string('group'); // Nama grup beserta tanggal
                $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luggage');
    }
};
