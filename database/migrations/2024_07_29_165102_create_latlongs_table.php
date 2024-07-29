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
        Schema::create('latlongs', function (Blueprint $table) {
            $table->id();
            $table->string('district')->nullable();
            $table->string('muncit')->nullable();
            $table->string('barangay')->nullable();
            $table->decimal('latitude',total:11, places:7);
            $table->decimal('longitude',total:10, places:7);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('latlongs');
    }
};
