<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sheba_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('price');
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->default('pending');
            $table->string('from_sheba_number', 26);
            $table->string('to_sheba_number', 26);
            $table->text('note')->nullable();
            $table->index(['status', 'created_at']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sheba_requests');
    }
};

