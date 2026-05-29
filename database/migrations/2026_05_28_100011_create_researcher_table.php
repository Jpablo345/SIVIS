<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('researcher', function (Blueprint $table) {
            $table->string('researcher_id', 10)->primary();
            $table->string('name_1', 50);
            $table->string('name_2', 50)->nullable();
            $table->string('last_name_1', 50);
            $table->string('last_name_2', 50)->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->string('cod_minciencias', 50)->nullable();

            $table->foreign('cod_minciencias')
                ->references('cod_minciencias')
                ->on('research_group')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });

        DB::statement("ALTER TABLE researcher ADD CONSTRAINT ck_email_format CHECK (email IS NULL OR email LIKE '%@%')");
    }

    public function down(): void
    {
        Schema::dropIfExists('researcher');
    }
};
