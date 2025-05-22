<?php

use App\Models\Contest;
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
        Schema::create('contest_user', function (Blueprint $table) {
            $table->id();
            $table->dateTime('end_time')->nullable();
            $table->integer('correct_answers')->default(0);
            $table->integer('gained_points')->nullable()->default(null);
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Contest::class)->constrained()->cascadeOnDelete();
            $table->unsignedInteger('rank')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contest_user');
    }
};
