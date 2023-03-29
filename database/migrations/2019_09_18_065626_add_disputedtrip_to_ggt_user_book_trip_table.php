<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisputedtripToGgtUserBookTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ggt_user_book_trip', function (Blueprint $table) {
            $table->longText('disputed_trip_response')->nullable()->after('close_trip_response');
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
            $table->dropColumn('disputed_trip_response');
        });
    }
}
