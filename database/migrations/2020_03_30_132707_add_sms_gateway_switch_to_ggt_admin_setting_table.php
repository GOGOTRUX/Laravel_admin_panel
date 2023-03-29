<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSmsGatewaySwitchToGgtAdminSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ggt_admin_setting', function (Blueprint $table) {
            $table->string('active_sms_gateway',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ggt_admin_setting', function (Blueprint $table) {
            $table->dropColumn('active_sms_gateway');
        });
    }
}
