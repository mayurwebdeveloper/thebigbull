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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('designation_id');
            $table->foreign('designation_id')->references('id')->on('designations');
            $table->string('unique_id', 8)->unique();
            $table->string('fname',30)->nullable();
            $table->string('fcm_token', 255)->nullable();
            $table->string('lname',30)->nullable();
            $table->string('email')->unique();
            $table->date('DOB')->nullable();
            $table->string('mo',15)->nullable();
            $table->text('address')->nullable();
            $table->text('photo')->nullable();
            $table->date('join_date')->nullable();
            $table->string('salary',10)->default(0);
            $table->string('password');
            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
