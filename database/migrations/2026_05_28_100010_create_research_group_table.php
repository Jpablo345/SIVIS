<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_group', function (Blueprint $table) {
            $table->string('cod_minciencias', 50)->primary();
            $table->string('group_name', 255);
            $table->string('group_classification', 10)->nullable();
            $table->unsignedBigInteger('institution_id')->nullable();

            $table->foreign('institution_id')
                ->references('institution_id')
                ->on('institution')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_group');
    }
};
