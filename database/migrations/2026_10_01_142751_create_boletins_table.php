<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('boletins', function (Blueprint $table) {
            $table->id();

            $table->foreignId('aluno_id')
                ->constrained('alunos')
                ->onDelete('cascade');

            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->onDelete('cascade');

            $table->year('ano');

            $table->enum('tipo_periodo', [
                'bimestre',
                'trimestre',
                'semestre'
            ]);

            $table->unsignedTinyInteger('numero_periodo');

            $table->text('observacao')->nullable();

            $table->string('arquivo_pdf')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletins');
    }
};