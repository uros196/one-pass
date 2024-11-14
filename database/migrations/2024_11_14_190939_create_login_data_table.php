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
        Schema::create('login_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->comment('alias: title');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('url')->nullable()->comment('website url');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_data');
    }
};
