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
        Schema::table('media', function (Blueprint $table) {
            // Embed items (YouTube/TikTok/Instagram) have no physical file.
            $table->string('path', 500)->nullable()->change();
            $table->string('embed_provider', 20)->nullable()->after('path');
            $table->string('embed_url', 1000)->nullable()->after('embed_provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['embed_provider', 'embed_url']);
            $table->string('path', 500)->nullable(false)->change();
        });
    }
};
