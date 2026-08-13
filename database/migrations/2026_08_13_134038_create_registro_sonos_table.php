<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registro_sonos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_id')->constrained('registro_diarios');
            $table->boolean('dormiu');
            $table->time('inicio_1')->nullable();
            $table->time('fim_1')->nullable();
            $table->time('inicio_2')->nullable();
            $table->time('fim_2')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_sonos');
    }
};
