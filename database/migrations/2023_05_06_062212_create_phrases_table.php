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
        Schema::create('phrases', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('phrase', 255);
            $table->string('author', 255);
            $table->string('source', 100);
            $table->string('category', 100);
            $table->string('status', 100)->default('pending');
            $table->string('language', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phrases');
    }
};
