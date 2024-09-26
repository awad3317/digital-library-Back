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
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number');
            $table->string('description',1024)->nullable();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade')->OnUbdate('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->eOnUbdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
