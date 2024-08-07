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
        Schema::create('datapemasukan', function (Blueprint $table) {
            $table->bigIncrements('id_data'); // Primary key
            $table->string('name'); // Name of the income entry
            $table->text('description')->nullable(); // Description of the income entry, optional
            $table->date('date'); // Date of the income entry
            $table->decimal('jumlah', 15, 2); // Amount of income
            $table->timestamps(); // Created at and updated at timestamps
            $table->unsignedBigInteger('id')->nullable();
            $table->foreign('id')->references('id')->on('categories')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datapemasukan'); // Drop table if rolling back migration
    }
};
