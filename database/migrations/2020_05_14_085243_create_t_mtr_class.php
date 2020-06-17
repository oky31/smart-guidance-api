<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMtrClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_mtr_class', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nik', 8);
            $table->string('class_code', 10);
            $table->string('activation_code', 8);
            $table->enum('status', ['1', '0']);    
            $table->timestamp('registered_at',0)->default(DB::raw('CURRENT_TIMESTAMP'));;
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
        Schema::dropIfExists('t_mtr_class');
    }
}
