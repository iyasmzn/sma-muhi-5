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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('slug', 180)->unique();
            $table->string('category', 100)->nullable()->comment('Misal: Akademik, Ekstrakurikuler, Keagamaan');
            $table->string('icon', 16)->nullable()->comment('Emoji ikon program');
            $table->string('excerpt', 300)->nullable();
            $table->longText('description');
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false)->comment('Tampil di section landing page (maks. 6)');
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
