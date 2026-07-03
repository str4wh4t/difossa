<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('competition_registration_members')) {
            Schema::create('competition_registration_members', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('competition_registration_id');
                $table->string('name');
                $table->string('email');
                $table->string('affiliation');
                $table->timestamps();

                $table->foreign('competition_registration_id', 'crm_registration_id_foreign')
                    ->references('id')
                    ->on('competition_registrations')
                    ->cascadeOnDelete();
            });

            return;
        }

        Schema::table('competition_registration_members', function (Blueprint $table) {
            if (! $this->foreignKeyExists('competition_registration_members', 'crm_registration_id_foreign')) {
                $table->foreign('competition_registration_id', 'crm_registration_id_foreign')
                    ->references('id')
                    ->on('competition_registrations')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_registration_members');
    }

    private function foreignKeyExists(string $table, string $name): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();

        $result = $connection->select(
            'select constraint_name from information_schema.table_constraints where table_schema = ? and table_name = ? and constraint_name = ? and constraint_type = ?',
            [$database, $table, $name, 'FOREIGN KEY'],
        );

        return count($result) > 0;
    }
};
