<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnsToGgtDriverKatta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('ggt_driver_katta', function (Blueprint $table) {
        //     $table->string('katta_up_slider',500)->nullable()->after('admin_id');
        //     $table->string('katta_useful_up_link',500)->nullable();
        //     $table->string('katta_bottom_slider',500)->nullable();
        //     $table->string('katta_useful_down_link',500)->nullable();
        //     $table->string('katta_mt_tips_image',255)->nullable();
        //     $table->string('katta_bd_assistance',500)->nullable();
        //     $table->string('katta_load_unload_image', 255)->nullable();
        //     $table->string('katta_essential_tool_image', 255)->nullable();
        // });
        
        Schema::create('ggt_driver_katta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id');
            $table->string('katta_up_slider',500)->nullable();
            $table->string('driver_katta_text', 255);
            $table->string('driver_katta_image', 255);
            $table->timestamps();
            $table->string('created_by', 255)->nullable();
            $table->softDeletes();
            $table->string('katta_useful_up_link',500)->nullable();
            $table->string('katta_bottom_slider',500)->nullable();
            $table->string('katta_useful_down_link',500)->nullable();
            $table->string('katta_mt_tips_image',255)->nullable();
            $table->string('katta_bd_assistance',500)->nullable();
            $table->string('katta_load_unload_image', 255)->nullable();
            $table->string('katta_essential_tool_image', 255)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ggt_driver_katta');
        // Schema::table('ggt_driver_katta', function (Blueprint $table) {
        //     //
        // });
    }
}
 

