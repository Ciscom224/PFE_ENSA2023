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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('adress',200);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->unsignedBigInteger('speciality_id')->nullable();
            $table->boolean('online')->default(false);
            $table->rememberToken();


            $table->foreign('speciality_id')->references('speciality_id')->on('specialities');
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
