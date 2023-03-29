<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ggt_master_admin_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 500);
            $table->integer('account_num');
            $table->string('ifsc_code', 500);
            $table->string('bank_name', 500)->nullable();
            $table->string('branch_name', 500)->nullable();
            $table->string('city', 500)->nullable();
            $table->tinyInteger('is_selected')->default(0);
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
        Schema::dropIfExists('ggt_master_admin_banks');
    }
}
