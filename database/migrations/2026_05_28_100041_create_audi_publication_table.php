<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audi_publication', function (Blueprint $table) {
            $table->id('consecutivo');
            $table->unsignedBigInteger('publication_id')->nullable();
            $table->string('title', 500)->nullable();
            $table->string('publication_year', 4)->nullable();
            $table->timestamp('fecha_registro')->nullable();
            $table->string('usuario', 20)->nullable();
            $table->char('accion', 1)->nullable();
        });

        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE FUNCTION public.audi_publication_func() RETURNS TRIGGER AS $TRG_GRABAR_AUDI_PUBLICATION$
            BEGIN
                IF (TG_OP = 'UPDATE') THEN
                    INSERT INTO public.audi_publication (consecutivo, publication_id, title, publication_year, fecha_registro, usuario, accion)
                    VALUES (DEFAULT, OLD.publication_id, OLD.title, OLD.publication_year, CURRENT_TIMESTAMP(0), CURRENT_USER, 'U');
                    RETURN NEW;
                ELSIF (TG_OP = 'DELETE') THEN
                    INSERT INTO public.audi_publication (consecutivo, publication_id, title, publication_year, fecha_registro, usuario, accion)
                    VALUES (DEFAULT, OLD.publication_id, OLD.title, OLD.publication_year, CURRENT_TIMESTAMP(0), CURRENT_USER, 'D');
                    RETURN OLD;
                END IF;
            END;
            $TRG_GRABAR_AUDI_PUBLICATION$ LANGUAGE PLPGSQL;

            CREATE TRIGGER TRG_GRABAR_AUDI_PUBLICATION
            BEFORE UPDATE OR DELETE ON public.publication
            FOR EACH ROW EXECUTE FUNCTION public.audi_publication_func();
        SQL);
    }

    public function down(): void
    {
        DB::unprepared(<<<'SQL'
            DROP TRIGGER IF EXISTS TRG_GRABAR_AUDI_PUBLICATION ON public.publication;
            DROP FUNCTION IF EXISTS public.audi_publication_func();
        SQL);

        Schema::dropIfExists('audi_publication');
    }
};
