<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGgtMitrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ggt_mitr', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id');
            $table->string('ggt_mitr_text', 255);
            $table->string('ggt_mitr_image',255)->nullable();
            $table->timestamps();
            $table->string('created_by', 255)->nullable();
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
        Schema::dropIfExists('ggt_mitr');
    }
}
