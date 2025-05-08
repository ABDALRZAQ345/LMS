<?php

use App\Models\Contest;
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
        Schema::create('student_contest', function (Blueprint $table) {
            $table->id();
            $table->dateTime('end_time');
            $table->integer('correct_answers');
            $table->integer('gained_points');
            $table->foreignIdFor(Student::class, 'student_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Contest::class, 'contest_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('rank');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_contest');
    }
};
