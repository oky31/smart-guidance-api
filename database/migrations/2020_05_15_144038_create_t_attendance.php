<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_attendance', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->unsignedBigInteger('id_schedule');
            $table->foreign('id_schedule')->references('id')->on('t_schedule');                                
            $table->string('nim', 8);
            $table->enum('status', ['0','1','2']); 
            $table->timestamp('join_date')->default(DB::raw('CURRENT_TIMESTAMP'));;
            $table->timestamp('attendance_start_date_time')->default(DB::raw('CURRENT_TIMESTAMP'));;
            $table->timestamp('attendance_end_date_time')->default(DB::raw('CURRENT_TIMESTAMP'));;
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
        Schema::dropIfExists('t_attendance');
    }
}
