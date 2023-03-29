<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToSubscriptionplanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // migration not ruuning on live
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->tinyInteger('is_sent_for_approval')->default(0)->after('is_active');
            $table->tinyInteger('is_approved')->default(0)->after('is_sent_for_approval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
           $table->dropColumn('is_sent_for_approval');
           $table->dropColumn('is_approved');
        });
    }
}
