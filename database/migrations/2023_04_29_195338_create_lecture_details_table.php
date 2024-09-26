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
        Schema::create('lecture_details', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('description',1024)->nullable();
            $table->foreignId('lecture_id')->constrained('lecturess')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_details');
    }
};
