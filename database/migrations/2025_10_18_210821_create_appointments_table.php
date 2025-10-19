<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('client');
            $table->text('service');
            $table->string('cellphone');
            $table->string('mechanic')->nullable();
            $table->text('notes')->nullable();
            $table->date('date');
            $table->time('time');
            $table->enum('status', ['pendente', 'concluido', 'cancelado'])->default('pendente');
            $table->text('cancel_reason')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
