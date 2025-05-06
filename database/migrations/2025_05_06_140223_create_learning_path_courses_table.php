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
        Schema::create('learning_path_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order');
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('learning_path_id')->constrained('learning_paths');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_path_courses');
    }
};
