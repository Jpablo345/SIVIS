<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article', function (Blueprint $table) {
            $table->unsignedBigInteger('publication_id')->primary();
            $table->string('journal_issn', 20);
            $table->string('doi', 200)->nullable()->unique();

            $table->foreign('publication_id')
                ->references('publication_id')
                ->on('publication')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('journal_issn')
                ->references('journal_issn')
                ->on('journal')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article');
    }
};
