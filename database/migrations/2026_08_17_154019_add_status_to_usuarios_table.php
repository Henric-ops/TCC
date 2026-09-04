<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('usuarios', 'status')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('status')
                    ->default('pendente')
                    ->after('perfil');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('usuarios', 'status')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};