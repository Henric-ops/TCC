<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mensagens', function (Blueprint $table) {
            $table->string('assunto')->after('aluno_id');
            $table->text('conteudo')->after('assunto');
            $table->string('status')->default('enviado')->after('conteudo');
        });
    }

    public function down(): void
    {
        Schema::table('mensagens', function (Blueprint $table) {
            $table->dropColumn([
                'assunto',
                'conteudo',
                'status',
            ]);
        });
    }
};