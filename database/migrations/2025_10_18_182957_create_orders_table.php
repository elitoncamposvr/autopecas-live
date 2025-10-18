<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('client');
            $table->string('os_reference')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->date('expected_delivery')->nullable();
            $table->string('carrier')->nullable();

            $table->enum('status', ['pendente', 'andamento', 'concluido', 'cancelado'])->default('pendente');

            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
