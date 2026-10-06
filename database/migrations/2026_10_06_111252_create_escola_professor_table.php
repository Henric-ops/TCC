<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('escola_professor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('escola_professor');
    }
};