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
        Schema::create('patients', function (Blueprint $table) {
            $table->string("patient_id")->unique();
            // $table->string("email",100);
            $table->string("first_name",100);
            $table->string("middle_name",100)->nullable();
            $table->string("last_name",100);
            $table->date("regDate");
            $table->date("birth_day");
            $table->string("adress",100);
            $table->string("city",100);
            $table->string("profil_image",100);
            $table->bigInteger("phone_1");
            $table->bigInteger("phone_2");
            $table->string('gender',15);
            $table->string('blood_group',5);
            $table->string('is_chronic');
            $table->string('is_allergy');
            $table->timestamps();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
