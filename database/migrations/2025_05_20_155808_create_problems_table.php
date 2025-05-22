<?php

use App\Models\Contest;
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
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignIdFor(Contest::class)->nullable()->constrained()->nullOnDelete();
            $table->text('description');
            $table->integer('time_limit')->default(1);
            $table->integer('memory_limit')->default(256);
            $table->text('input');
            $table->text('output');
            $table->text('test_input');
            $table->text('expected_output');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problems');
    }
};
