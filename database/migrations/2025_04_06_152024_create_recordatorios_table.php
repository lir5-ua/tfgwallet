<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->date('fecha');
            $table->text('descripcion')->nullable();
            $table->boolean('realizado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
    }
};
