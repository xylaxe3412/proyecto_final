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
            $table->string('template_id')->nullable()->after('current_day');
            $table->string('template_version', 10)->default('1.0')->after('template_id');
            $table->json('custom_settings')->nullable()->after('template_version');
            $table->boolean('sync_enabled')->default(true)->after('custom_settings');
            $table->timestamp('last_synced_at')->nullable()->after('sync_enabled');
            $table->text('sync_notes')->nullable()->after('last_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn([
                'template_id',
                'template_version',
                'custom_settings',
                'sync_enabled',
                'last_synced_at',
                'sync_notes'
            ]);
        });
    }
};
