<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_leaders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('fcm_token')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignId('current_group_id')->nullable()->constrained('groups');
            $table->date('activation_start')->nullable();
            $table->date('activation_end')->nullable();
            $table->string('activation_code')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_leaders');
    }
};
