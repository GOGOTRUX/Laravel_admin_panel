<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToSubplanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->integer('subscription_no_of_veh_allowed')->nullable()->after('subscription_veh_wheel_type');
            $table->date('subscription_validity_from')->nullable()->change();
            $table->date('subscription_validity_to')->nullable()->change();
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
            $table->dropColumn('subscription_no_of_veh_allowed');
            $table->date('subscription_validity_from')->nullable();
            $table->date('subscription_validity_to')->nullable();
        });
    }
}
