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
        Schema::create('student_contests', function (Blueprint $table) {
            $table->id();
            $table->date('end_time');
            $table->integer('correct_answers');
            $table->integer('gained_points');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('contest_id')->constrained('contests');
            $table->unsignedInteger('rank');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_contests');
    }
};
