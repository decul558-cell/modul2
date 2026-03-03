<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 8)->primary();
            $table->string('nama', 50);
            $table->integer('harga');
            $table->timestamp('tgl_input')->useCurrent();
        });

        // Trigger PostgreSQL: auto-generate id_barang format YYMMDDNN
        DB::unprepared("
            CREATE OR REPLACE FUNCTION fn_trigger_id_barang()
            RETURNS TRIGGER AS \$\$
            DECLARE
                nr INTEGER := 0;
                tgl TEXT;
            BEGIN
                SELECT COUNT(id_barang) INTO nr
                FROM barang
                WHERE DATE(tgl_input) = CURRENT_DATE;

                nr := nr + 1;

                tgl := TO_CHAR(CURRENT_DATE, 'YYMMDD');

                NEW.id_barang := tgl || LPAD(nr::TEXT, 2, '0');

                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER trigger_id_barang
            BEFORE INSERT ON barang
            FOR EACH ROW
            EXECUTE FUNCTION fn_trigger_id_barang();
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_id_barang ON barang');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_trigger_id_barang');
        Schema::dropIfExists('barang');
    }
};