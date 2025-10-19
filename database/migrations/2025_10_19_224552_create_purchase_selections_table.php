<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('purchase_selections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('quote_id')->constrained('quotes')->onDelete('cascade');
            $table->foreignId('selected_by')->constrained('users');

            $table->timestamps('selected_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_selections');
    }
};
