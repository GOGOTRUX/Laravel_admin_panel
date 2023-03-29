<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGgtDriverOfMonthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ggt_driver_of_month', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id');
            $table->string('admin_email', 255)->nullable();
            $table->string('op_mobile_no', 20);
            $table->string('comment',255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ggt_driver_of_month');
    }
}
