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
        Schema::create('logins_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->comment('alias: title');
            $table->text('username')->nullable();
            $table->text('password')->nullable();
            $table->text('url')->nullable()->comment('website url');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logins_data');
    }
};
