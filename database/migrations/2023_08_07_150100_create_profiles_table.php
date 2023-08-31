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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username')->nullable();
            $table->string('name');
            $table->integer('age')->nullable();
            $table->string('email');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('country')->nullable();
            $table->string('profile_image')->nullable();
            // $table->string('image_path')->nullable();
            $table->timestamps();

            $table->foreign("user_id")->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
