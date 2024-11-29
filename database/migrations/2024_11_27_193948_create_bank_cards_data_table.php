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
        Schema::create('bank_cards_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->comment('alias: title');
            $table->string('number');
            $table->integer('number_length');
            $table->integer('identifier');
            $table->string('expire_date')->nullable();
            $table->string('cvc')->nullable();
            $table->string('pin')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('type')->default('none');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_cards_data');
    }
};
