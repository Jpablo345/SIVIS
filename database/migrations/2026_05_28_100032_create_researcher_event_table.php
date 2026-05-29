<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('researcher_event', function (Blueprint $table) {
            $table->id('event_part_id');
            $table->string('presentation_title', 500)->nullable();
            $table->string('participation_type', 30)->nullable();
            $table->unsignedBigInteger('event_id');
            $table->string('researcher_id', 10);

            $table->unique(['event_id', 'researcher_id']);

            $table->foreign('event_id')
                ->references('event_id')
                ->on('event')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('researcher_id')
                ->references('researcher_id')
                ->on('researcher')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        DB::statement("ALTER TABLE researcher_event ADD CONSTRAINT ck_participation_type CHECK (participation_type IN ('Ponente','Asistente','Organizador'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('researcher_event');
    }
};
