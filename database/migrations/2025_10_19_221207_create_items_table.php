<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('brand_desired')->nullable();
            $table->string('item_code')->nullable();
            $table->text('notes')->nullable();
            $table->integer('required_quantity')->default(1);
            $table->string('status')->default('quoting'); // quoting, negotiating, purchased, finalized
            $table->foreignId('created_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
