<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel categories.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap kategori
            $table->string('name')->unique(); // Nama kategori yang unik
             // Tambahkan kolom jenis_kategori
            $table->text('description')->nullable(); // Deskripsi kategori, bersifat opsional
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->softDeletes(); // Kolom deleted_at untuk soft delete
        });
    }

    /**
     * Batalkan migrasi untuk menghapus tabel.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
