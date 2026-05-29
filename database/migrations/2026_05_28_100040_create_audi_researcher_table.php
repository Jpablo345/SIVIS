<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audi_researcher', function (Blueprint $table) {
            $table->id('consecutivo');
            $table->string('researcher_id', 10)->nullable();
            $table->string('name_1', 100)->nullable();
            $table->string('last_name_1', 100)->nullable();
            $table->string('cod_minciencias', 50)->nullable();
            $table->timestamp('fecha_registro')->nullable();
            $table->string('usuario', 20)->nullable();
            $table->char('accion', 1)->nullable();
        });

        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE FUNCTION public.audi_researcher_func() RETURNS TRIGGER AS $TRG_GRABAR_AUDI_RESEARCHER$
            BEGIN
                IF (TG_OP = 'UPDATE') THEN
                    INSERT INTO public.audi_researcher (consecutivo, researcher_id, name_1, last_name_1, cod_minciencias, fecha_registro, usuario, accion)
                    VALUES (DEFAULT, OLD.researcher_id, OLD.name_1, OLD.last_name_1, OLD.cod_minciencias, CURRENT_TIMESTAMP(0), CURRENT_USER, 'U');
                    RETURN NEW;
                ELSIF (TG_OP = 'DELETE') THEN
                    INSERT INTO public.audi_researcher (consecutivo, researcher_id, name_1, last_name_1, cod_minciencias, fecha_registro, usuario, accion)
                    VALUES (DEFAULT, OLD.researcher_id, OLD.name_1, OLD.last_name_1, OLD.cod_minciencias, CURRENT_TIMESTAMP(0), CURRENT_USER, 'D');
                    RETURN OLD;
                END IF;
            END;
            $TRG_GRABAR_AUDI_RESEARCHER$ LANGUAGE PLPGSQL;

            CREATE TRIGGER TRG_GRABAR_AUDI_RESEARCHER
            BEFORE UPDATE OR DELETE ON public.researcher
            FOR EACH ROW EXECUTE FUNCTION public.audi_researcher_func();
        SQL);
    }

    public function down(): void
    {
        DB::unprepared(<<<'SQL'
            DROP TRIGGER IF EXISTS TRG_GRABAR_AUDI_RESEARCHER ON public.researcher;
            DROP FUNCTION IF EXISTS public.audi_researcher_func();
        SQL);

        Schema::dropIfExists('audi_researcher');
    }
};
