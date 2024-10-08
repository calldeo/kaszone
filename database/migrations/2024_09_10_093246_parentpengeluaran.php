<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('pengeluaran_parent', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->date('tanggal'); // Kolom tanggal
            $table->timestamps();

            // Menambahkan foreign key ke tabel pengeluaran
           // Menghapus detail jika pengeluaran dihapus
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
