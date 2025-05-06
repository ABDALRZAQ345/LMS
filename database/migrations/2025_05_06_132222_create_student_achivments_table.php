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
        Schema::create('student_achivments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achivment_id')->constrained('achivments');
            $table->foreignId('student_id')->constrained('students');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_achivments');
    }
};
