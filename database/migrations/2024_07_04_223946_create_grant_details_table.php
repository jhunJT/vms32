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
        Schema::create('grant_details', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('district')->nullable();
            $table->string('muncit')->nullable();
            $table->string('barangay')->nullable();
            $table->string('grant')->nullable();
            $table->string('office')->nullable();
            $table->string('coordinator')->nullable();
            $table->string('date')->nullable();
            $table->float('amount')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('vid')->nullable();
            $table->integer('uid')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grant_details');
    }
};
