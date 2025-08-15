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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('github_id')->nullable()->default(null)->unique();
            $table->string('github_token')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('fcm_token')->nullable();
            $table->longText('image')->nullable();
            $table->string('gitHub_account')->nullable();
            $table->string('bio')->nullable();
            $table->integer('points')->default(0);
            $table->dateTime('last_online')->nullable();
            $table->boolean('email_verified')->default(false);
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'expert'])
                ->nullable()
                ->default('beginner');
            $table->string('google_id')->nullable()->unique();
            $table->enum('role', ['admin', 'student', 'teacher'])->default('student');
            $table->integer('age')->nullable()->default(null);
            $table->date('birth_date')->nullable()->default(null);
            $table->boolean('active')->default(true);
            $table->float('balance')->default(0);
            $table->rememberToken();


            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
