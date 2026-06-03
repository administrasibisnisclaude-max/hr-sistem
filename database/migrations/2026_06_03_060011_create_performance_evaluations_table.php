<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->string('period'); // e.g. "2024-Q1", "2024-01"
            $table->decimal('score', 5, 2)->default(0);
            $table->enum('grade', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->text('notes')->nullable();
            $table->json('criteria')->nullable(); // scoring breakdown
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_evaluations');
    }
};
