<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnToGgtDriverKattaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ggt_driver_katta', function (Blueprint $table) {
            $table->text('katta_useful_up_link')->nullable()->change();
            $table->text('katta_useful_down_link')->nullable()->change();
            $table->text('katta_bd_assistance')->nullable()->change();

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ggt_driver_katta', function (Blueprint $table) {
            $table->string('katta_useful_up_link',500)->nullable();
            $table->string('katta_useful_down_link',500)->nullable();
            $table->string('katta_bd_assistance',500)->nullable();            
        });
    }
}