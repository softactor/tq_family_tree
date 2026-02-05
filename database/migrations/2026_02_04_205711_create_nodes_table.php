<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('user_id')->nullable(); // Reference to users table
            $table->string('first_name'); // First Name
            $table->string('last_name')->nullable(); // Last Name
            $table->enum('gender', ['male', 'female', 'other']); // Gender
            $table->date('dob')->nullable(); // Date of Birth
            $table->date('dod')->nullable(); // Date of Death
            $table->string('profile_photo')->nullable(); // Photo Path
            $table->timestamps(); // Created and Updated At

            // Foreign key to the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nodes');
    }
};
