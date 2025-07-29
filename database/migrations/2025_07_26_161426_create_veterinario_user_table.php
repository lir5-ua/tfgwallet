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
        Schema::create('veterinario_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('veterinario_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('veterinario_id')->references('id')->on('veterinarios')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Evitar duplicados
            $table->unique(['veterinario_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veterinario_user');
    }
};
