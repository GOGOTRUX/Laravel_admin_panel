<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLatLongToGgtUserBookTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ggt_user_book_trip', function (Blueprint $table) {
            $table->string('start_address_lat',100)->nullable()->change();
            $table->string('start_address_lan',100)->nullable()->change();
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
            $table->dropColumn('start_address_lat');
            $table->dropColumn('start_address_lan');
        });
    }
}
