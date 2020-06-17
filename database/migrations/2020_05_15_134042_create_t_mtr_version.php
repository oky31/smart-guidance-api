<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMtrVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_mtr_version', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('allow_sdk_version')->unsigned();
            $table->string('api_key',20);
            $table->enum('allow', ['0', '1']);       
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_mtr_version');
    }
}
