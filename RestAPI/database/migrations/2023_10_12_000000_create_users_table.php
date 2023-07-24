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
            $table->string('user_first_name');
            $table->string('user_last_name');
            $table->string('adress',200);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->softDeletes();
            $table->boolean('online')->default(false);
            $table->unsignedBigInteger('speciality_id')->nullable();
            $table->foreign('speciality_id')->references('speciality_id')->on('specialities');


            $table->rememberToken();

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
