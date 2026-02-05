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
        Schema::create('relationships', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('node1_id'); // Reference to first individual
            $table->unsignedBigInteger('node2_id'); // Reference to second individual
            $table->enum('relationship_type', ['parent', 'child', 'spouse', 'sibling']); // Relationship Type
            $table->timestamps(); // Created and Updated At

            // Foreign key constraints
            $table->foreign('node1_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->foreign('node2_id')->references('id')->on('nodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relationships');
    }
};
