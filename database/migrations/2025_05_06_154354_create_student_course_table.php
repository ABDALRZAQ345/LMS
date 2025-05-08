<?php

use App\Models\Course;
use App\Models\Student;
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
        Schema::create('student_course', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('paid')->comment('how much student paid that course');
            $table->enum('status', ['finished', 'enrolled', 'watch_later']);
            $table->foreignIdFor(Student::class, 'student_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Course::class, 'course_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_courses');
    }
};
