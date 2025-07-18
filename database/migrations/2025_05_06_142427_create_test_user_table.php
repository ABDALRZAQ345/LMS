<?php

use App\Models\Course;
use App\Models\Test;
use App\Models\User;
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
        Schema::create('test_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('correct_answers')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Test::class)->constrained()->cascadeOnDelete();
        //    $table->foreignIdFor(Course::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_user');
    }
};
