<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution', function (Blueprint $table) {
            $table->id('institution_id');
            $table->string('institution_name', 255)->unique();
            $table->string('country', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('institution_type', 50)->nullable();
            $table->string('website', 255)->nullable();
        });

        DB::statement("ALTER TABLE institution ADD CONSTRAINT ck_website_format CHECK (website IS NULL OR website LIKE 'http%')");
    }

    public function down(): void
    {
        Schema::dropIfExists('institution');
    }
};
