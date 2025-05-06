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
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('time');
            $table->text('description');
            $table->unsignedInteger('level');
            $table->enum('status', ['active', 'ended','coming soon'])->default('coming soon');
            $table->dateTime('start_at');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->string('verified');

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
