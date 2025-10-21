<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Permitir logs globais
            $table->foreignId('item_id')->nullable()->change();

            // Campos adicionais
            $table->string('module')->nullable()->after('user_id'); // Ex: orders, appointments, quotes
            $table->text('details')->nullable()->after('new_value');
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['module', 'details']);
            $table->foreignId('item_id')->nullable(false)->change();
        });
    }
};
