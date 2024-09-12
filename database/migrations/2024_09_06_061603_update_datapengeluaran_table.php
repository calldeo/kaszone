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
         Schema::table('datapengeluaran', function (Blueprint $table) {
            //
                $table->integer('jumlah_satuan')->after('jumlah');
                $table->decimal('nominal',15,2)->after('jumlah_satuan');
                $table->decimal('dll', 15,2)->after('nominal');
                $table->string('image')->after('dll')->nullable();




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
