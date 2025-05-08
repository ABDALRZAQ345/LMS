<?php

use App\Models\Achievement;
use App\Models\Achivment;
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
        Schema::create('student_achievement', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Achievement::class, 'achievement_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Student::class, 'student_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_achievement');
    }
};
