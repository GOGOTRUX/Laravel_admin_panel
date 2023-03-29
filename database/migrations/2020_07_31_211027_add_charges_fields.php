<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChargesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ggt_user_book_trip', function (Blueprint $table) {
            $table->double('cust_waiting_charges', 8, 2)->nullable()->after('close_trip_response');
            $table->double('partner_waiting_charges', 8, 2)->nullable()->after('close_trip_response');
            $table->double('incidental_charges', 8, 2)->nullable()->after('close_trip_response');
            $table->double('accidental_charges', 8, 2)->nullable()->after('close_trip_response');
            $table->double('other_charges', 8, 2)->nullable()->after('close_trip_response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ggt_user_book_trip', function (Blueprint $table) {
            $table->dropColumn('cust_waiting_charges');
            $table->dropColumn('partner_waiting_charges');
            $table->dropColumn('incidental_charges');
            $table->dropColumn('accidental_charges');
            $table->dropColumn('other_charges');
        });
    }
}
