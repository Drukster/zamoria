<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('duas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('title')
                ->nullable();
            $table->string('content')
                ->nullable();
            $table->string('arabic_text')
                ->nullable();
            $table->string('translation')
                ->nullable();
            $table->string('transliteration')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duas');
    }
};
