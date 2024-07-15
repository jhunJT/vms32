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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role',['admin','supervisor','superuser','encoder']);
            $table->enum('status',['active','inactive']);
            $table->string('birthday')->nullable();
            $table->string('district')->nullable();
            $table->string('muncit')->nullable();
            $table->string('tbname')->nullable();
            $table->string('ulat',10,7)->nullable();
            $table->string('ulong',11,7)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
