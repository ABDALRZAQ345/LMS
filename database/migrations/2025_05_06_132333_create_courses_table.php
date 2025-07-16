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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->foreignIdFor(User::class)
                ->comment('teacher id (creator)')
                ->constrained()->cascadeOnDelete();
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'expert'])
                ->nullable()
                ->default('beginner');
            $table->decimal('price')->default(0);
            $table->boolean('verified')->default(false);
            $table->enum('request_status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->decimal('rate')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
