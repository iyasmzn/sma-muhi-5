<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('contact_items');
    }

    public function down(): void
    {
        Schema::create('contact_items', function ($table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('label');
            $table->string('value');
            $table->string('link')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
