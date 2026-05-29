<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal', function (Blueprint $table) {
            $table->string('journal_issn', 20)->primary();
            $table->string('journal_name', 255);
            $table->string('category', 10)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal');
    }
};
