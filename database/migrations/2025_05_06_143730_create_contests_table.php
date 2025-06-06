<?php

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
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('time')->comment('period of time of the contest');
            $table->text('description')->nullable();
            $table->enum('type', ['quiz', 'programming'])->default('quiz');
            $table->Enum('level',['beginner','intermediate','advanced','expert'])->default('beginner');
            $table->enum('status', ['active', 'ended', 'coming'])->default('coming');
            $table->dateTime('start_at');
            $table->foreignIdFor(User::class)
                ->comment('teacher id (creator)')
                ->constrained()->cascadeOnDelete();

            $table->enum('request_status', ['pending', 'accepted', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contests');
    }
};
