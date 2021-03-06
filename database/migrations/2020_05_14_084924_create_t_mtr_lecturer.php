<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMtrLecturer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_mtr_lecturer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nik', 8);
            $table->string('firstname', 20);
            $table->string('middlename', 20);
            $table->string('lastname', 20);  
            $table->string('faculty_code', 8);            
            $table->string('major_code', 8);
            $table->string('phone_number', 15);
            $table->timestamp('registered_at', 0)->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('t_mtr_lecturer');
    }
}
