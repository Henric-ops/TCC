<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('boletim_documentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('boletim_id')
                ->constrained('boletins')
                ->cascadeOnDelete();

            $table->string('nome_original');
            $table->string('mime', 100)->default('application/pdf');
            $table->unsignedInteger('tamanho');
            $table->timestamps();
        });


        DB::statement(
            'ALTER TABLE boletim_documentos ADD conteudo LONGBLOB NOT NULL'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('boletim_documentos');
    }
};