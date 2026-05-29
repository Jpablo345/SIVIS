<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book', function (Blueprint $table) {
            $table->unsignedBigInteger('publication_id')->primary();
            $table->string('book_isbn', 20)->unique();
            $table->string('means_of_dissemination', 100)->nullable();
            $table->string('editorial', 255)->nullable();
            $table->unsignedBigInteger('book_type_id')->nullable();

            $table->foreign('publication_id')
                ->references('publication_id')
                ->on('publication')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('book_type_id')
                ->references('book_type_id')
                ->on('book_type')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book');
    }
};
