<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGgtCustomerDynamicImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ggt_customer_dynamic_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id');
            $table->string('created_by', 50)->nullable();
            $table->string('image_name', 500)->nullable();
            $table->string('image_type', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ggt_customer_dynamic_images');
    }
}
