<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_expiration_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensitive_data_connection_id')->constrained()->cascadeOnDelete();
            $table->date('expires_at');
            $table->boolean('is_notified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_expiration_times');
    }
};
