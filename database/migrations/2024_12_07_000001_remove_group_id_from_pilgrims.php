<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pilgrims', function (Blueprint $table) {
            // Drop foreign key jika ada
            $foreignKeys = $this->listTableForeignKeys('pilgrims');
            if (in_array('group_id', $foreignKeys)) {
                $table->dropForeign(['group_id']);
            }
            
            // Drop column jika ada
            if (Schema::hasColumn('pilgrims', 'group_id')) {
                $table->dropColumn('group_id');
            }
        });
    }

    public function down()
    {
        Schema::table('pilgrims', function (Blueprint $table) {
            if (!Schema::hasColumn('pilgrims', 'group_id')) {
                $table->foreignId('group_id')->nullable()->constrained();
            }
        });
    }

    private function listTableForeignKeys($table)
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();
        return array_map(function($key) {
            return $key->getLocalColumns()[0];
        }, $conn->listTableForeignKeys($table));
    }
};