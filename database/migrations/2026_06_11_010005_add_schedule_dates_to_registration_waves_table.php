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
        Schema::table('registration_waves', function (Blueprint $table) {
            $table->date('selection_date')->nullable()->after('end_date');
            $table->date('announcement_date')->nullable()->after('selection_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_waves', function (Blueprint $table) {
            $table->dropColumn(['selection_date', 'announcement_date']);
        });
    }
};
