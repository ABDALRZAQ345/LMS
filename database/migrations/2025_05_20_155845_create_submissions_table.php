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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Problem::class)->constrained()->cascadeOnDelete();
            $table->enum('language', ['cpp', 'python','java','csharp']);
            $table->longText('code');
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'wrong_answer', 'error', 'runtime_error', 'memory_limit_exceeded', 'time_limit_exceeded','compile_error'])->default('pending');
            $table->longText('output')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
