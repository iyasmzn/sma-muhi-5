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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('nickname', 50)->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('major', 100)->nullable();
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->string('certificate_number', 100)->nullable()->unique();
            $table->string('instagram', 150)->nullable();
            $table->string('twitter', 150)->nullable();
            $table->string('facebook', 150)->nullable();
            $table->string('youtube', 150)->nullable();
            $table->string('occupation', 150)->nullable();
            $table->boolean('entered_ptn')->default(false);
            $table->string('ptn_name', 150)->nullable();
            $table->timestamps();

            $table->index('graduation_year');
            $table->index('entered_ptn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
