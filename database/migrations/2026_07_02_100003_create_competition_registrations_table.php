<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competition_id')->constrained()->restrictOnDelete();
            $table->foreignId('competition_category_id')->constrained()->restrictOnDelete();
            $table->string('article_title');
            $table->text('article_summary');
            $table->string('article_file');
            $table->timestamps();

            $table->unique(['user_id', 'competition_id', 'competition_category_id'], 'user_competition_category_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_registrations');
    }
};
