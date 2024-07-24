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
        Schema::create('grantsdrps', function (Blueprint $table) {
            $table->id();
            $table->string('grant_type')->nullable();
            $table->string('date_of_grant')->nullable();
            $table->string('grant_amount')->nullable();
            $table->string('g_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grantsdrps');
    }
};
