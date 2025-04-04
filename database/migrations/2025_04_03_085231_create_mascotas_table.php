<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Especie;
use App\Enums\Sexo;
return new class extends Migration
{



    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mascotas', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->enum('especie', array_map(fn($e) => $e->value, Especie::cases()));
                    $table->string('raza')->nullable();
                    $table->string('nombre');
                    $table->date('fecha_nacimiento')->nullable();
                    $table->enum('sexo', ['M', 'H'])->nullable();
                    $table->text('notas')->nullable();
                    $table->string('imagen')->nullable();
                    $table->timestamps();

                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
