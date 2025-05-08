<?php

use App\Models\Student;
use App\Models\Test;
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
        Schema::create('student_test', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('correct_answers')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->foreignIdFor(Student::class, 'student_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Test::class, 'test_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_test');
    }
};
