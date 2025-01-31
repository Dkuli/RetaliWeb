<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_leaders', function (Blueprint $table) {
            $table->timestamp('last_active_at')->nullable()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('tour_leaders', function (Blueprint $table) {
            $table->dropColumn('last_active_at');
        });
    }
};
