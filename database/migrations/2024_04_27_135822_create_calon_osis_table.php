<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalonOsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calon_osis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nama_calon', 35);
            $table->text('visimisi');
            $table->string('NIS', 255);
            $table->string('kelas', 25);
            $table->string('gambar', 50);
            $table->string('slogan', 255);
            $table->integer('jumlah_vote')->nullable();
            $table->string('periode', 12);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calon_osis');
    }
}