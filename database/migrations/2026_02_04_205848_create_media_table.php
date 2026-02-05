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
        Schema::create('media', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('node_id'); // Reference to the nodes table
            $table->string('file_path'); // File Path
            $table->enum('type', ['photo', 'document', 'video']); // Media Type
            $table->timestamps(); // Created and Updated At

            // Foreign key constraint
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
};
