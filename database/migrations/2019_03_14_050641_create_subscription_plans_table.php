<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->increments('subscription_id');
            $table->integer('subscription_type_id');
            $table->integer('subscription_amount');
            $table->integer('subscription_base_amount')->nullable();
            $table->string('subscription_desc',255);
            $table->string('subscription_validity_type', 255);
            $table->date('subscription_validity');
            $table->string('subscription_image', 1024)->nullable();
            $table->string('subscription_veh_wheel_type', 255);
            $table->string('subscription_op_type', 255)->nullable();
            $table->integer('subscription_bid_advantage')->nullable();
            $table->integer('subscription_priority_scale')->nullable();
            $table->integer('subscription_efficency')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->tinyInteger('is_active')->default(0);
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
        Schema::dropIfExists('subscription_plans');
    }
}
