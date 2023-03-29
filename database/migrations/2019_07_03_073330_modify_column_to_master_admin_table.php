<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnToMasterAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ggt_master_admin_banks', function (Blueprint $table) {
            $table->bigInteger('account_num')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ggt_master_admin_banks', function (Blueprint $table) {
            $table->integer('account_num')->change();
        });
    }
}
