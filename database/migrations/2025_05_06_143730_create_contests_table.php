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
            $table->unsignedInteger('level')->default(0);
            $table->enum('status', ['active', 'ended', 'coming'])->default('coming');
            $table->dateTime('start_at');
            $table->foreignIdFor(User::class)
                ->comment('teacher id (creator)')
                ->constrained()->cascadeOnDelete();
            $table->boolean('verified')->default(false);

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
