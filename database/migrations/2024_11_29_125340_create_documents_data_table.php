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
        Schema::create('documents_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('name')->comment('alias: title');
            $table->text('number')->nullable();
            $table->text('issue_date')->nullable();
            $table->text('expire_date')->nullable();
            $table->text('place_of_issue')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_data');
    }
};
