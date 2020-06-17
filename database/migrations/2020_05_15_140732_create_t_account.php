<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 40);
            $table->string('identity_number', 8);
            $table->string('password', 100);  
            $table->enum('status_account', ['0', '1']);    
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
        Schema::dropIfExists('t_account');
    }
}
