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
        Schema::table('habits', function (Blueprint $table) {
            $table->string('frequency')->default('diario');
            $table->text('motivation')->nullable();
            $table->string('reward')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn(['frequency', 'motivation', 'reward', 'is_completed', 'last_completed_at']);
        });
    }
};
