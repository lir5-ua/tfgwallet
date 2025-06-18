<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verificar si el índice existe antes de eliminarlo (MySQL)
            $indexExists = collect(DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_name_unique'"))
                ->isNotEmpty();
            
            if ($indexExists) {
                $table->dropUnique(['name']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('name');
        });
    }
};
