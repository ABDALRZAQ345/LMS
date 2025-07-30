<?php

use App\Models\Tag;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->json('technologies')->nullable();
            $table->json('links')->nullable();
            $table->integer('likes')->default(0);
            $table->enum('status', ['pending', 'accepted', 'refused'])->default('pending');
            $table->longText('image')->nullable();
            $table->foreignIdFor(Tag::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
