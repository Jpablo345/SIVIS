<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('researcher_publication', function (Blueprint $table) {
            $table->unsignedBigInteger('publication_id');
            $table->string('researcher_id', 10);
            $table->unsignedInteger('author_order')->nullable();

            $table->primary(['publication_id', 'researcher_id']);

            $table->foreign('publication_id')
                ->references('publication_id')
                ->on('publication')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('researcher_id')
                ->references('researcher_id')
                ->on('researcher')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        DB::statement("ALTER TABLE researcher_publication ADD CONSTRAINT ck_author_order CHECK (author_order > 0)");
    }

    public function down(): void
    {
        Schema::dropIfExists('researcher_publication');
    }
};
