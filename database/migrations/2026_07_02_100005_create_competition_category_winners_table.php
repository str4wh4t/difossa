<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_category_winners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_category_id');
            $table->unsignedBigInteger('competition_registration_id');
            $table->unsignedInteger('rank');
            $table->timestamps();

            $table->foreign('competition_category_id', 'ccw_category_id_foreign')
                ->references('id')
                ->on('competition_categories')
                ->restrictOnDelete();
            $table->foreign('competition_registration_id', 'ccw_registration_id_foreign')
                ->references('id')
                ->on('competition_registrations')
                ->restrictOnDelete();

            $table->unique(['competition_category_id', 'rank'], 'ccw_category_rank_unique');
            $table->unique(['competition_category_id', 'competition_registration_id'], 'ccw_category_registration_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_category_winners');
    }
};
