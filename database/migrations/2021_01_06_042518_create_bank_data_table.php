<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_data', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('name_id')->nulable();
            $table->string('data_cod',500);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('bank_data', function (Blueprint $table) {
            $table->foreign('name_id')
                ->references('id')
                ->on('bank_name')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_data');
    }
}
