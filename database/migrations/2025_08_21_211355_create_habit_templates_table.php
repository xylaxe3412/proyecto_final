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
        Schema::create('habit_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_id')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('categoria');
            $table->string('version', 10)->default('1.0');
            $table->json('content');
            $table->json('quiz_questions')->nullable();
            $table->json('steps')->nullable();
            $table->json('tips')->nullable();
            $table->json('motivational_quotes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('duration_days')->default(30);
            $table->string('difficulty_level')->default('beginner');
            $table->text('changelog')->nullable();
            $table->timestamps();

            $table->index(['template_id', 'version']);
            $table->index('categoria');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_templates');
    }
};
