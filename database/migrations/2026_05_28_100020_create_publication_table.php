<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication', function (Blueprint $table) {
            $table->id('publication_id');
            $table->string('title', 255);
            $table->string('publication_year', 4)->nullable();
            $table->string('scope', 50)->nullable();
            $table->string('country_publication', 100)->nullable();
            $table->string('url', 300)->nullable();
            $table->unsignedBigInteger('type_id')->nullable();

            $table->foreign('type_id')
                ->references('type_id')
                ->on('publication_type')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        DB::statement("ALTER TABLE publication ADD CONSTRAINT ck_scope CHECK (scope IN ('Nacional','Internacional'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('publication');
    }
};
