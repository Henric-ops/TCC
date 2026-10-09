<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        DB::statement(
            'ALTER TABLE boletim_documentos MODIFY conteudo LONGBLOB NOT NULL'
        );
    }

    public function down(): void
    {
        DB::statement(
            'ALTER TABLE boletim_documentos MODIFY conteudo LONGTEXT NOT NULL'
        );
    }
};