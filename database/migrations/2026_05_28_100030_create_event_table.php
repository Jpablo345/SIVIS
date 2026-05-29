<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('event_name', 255);
            $table->string('event_year', 4)->nullable();
            $table->string('event_month', 30)->nullable();
            $table->string('event_scope', 50)->nullable();
            $table->string('event_url', 255)->nullable();
            $table->unsignedBigInteger('host_institution_id')->nullable();
            $table->unsignedBigInteger('origin_institution_id')->nullable();

            $table->foreign('host_institution_id')
                ->references('institution_id')
                ->on('institution')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('origin_institution_id')
                ->references('institution_id')
                ->on('institution')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });

        DB::statement("ALTER TABLE event ADD CONSTRAINT chk_event_scope CHECK (event_scope IN ('Nacional','Internacional'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
