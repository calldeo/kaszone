<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddAfterDeleteTriggerToHasilVoting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER after_delete_hasil_voting
            AFTER DELETE ON hasil_voting
            FOR EACH ROW
            BEGIN
                -- Mengurangi jumlah_vote di tabel calon_osis
                UPDATE calon_osis
                SET jumlah_vote = jumlah_vote - 1
                WHERE id = OLD.id_calon;

                -- Mengubah status_pemilihan di tabel users
                UPDATE users
                SET status_pemilihan = "Belum Memilih"
                WHERE id = OLD.id_user;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_hasil_voting');
    }
}