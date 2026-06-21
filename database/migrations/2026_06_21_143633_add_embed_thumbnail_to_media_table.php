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
            // Manually uploaded preview image for embeds whose provider has no
            // fetchable thumbnail (TikTok, Instagram). Null = use provider default.
            $table->string('embed_thumbnail_path', 500)->nullable()->after('embed_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('embed_thumbnail_path');
        });
    }
};
