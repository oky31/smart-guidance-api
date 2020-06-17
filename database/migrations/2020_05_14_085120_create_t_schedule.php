<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('class_code', 10);    
            $table->string('title_guidance', 20);            
            $table->enum('status', ['0', '1','2']); 
            $table->date('date');         
            $table->time('start_time');         
            $table->time('end_time');         
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
        Schema::dropIfExists('t_schedule');
    }
}
