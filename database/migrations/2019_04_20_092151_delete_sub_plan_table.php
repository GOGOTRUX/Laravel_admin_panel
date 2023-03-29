<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSubPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            Schema::dropIfExists('subscription_plans');
        });

        Schema::create('subscription_plans', function (Blueprint $table) {
          $table->increments('subscription_id');
          $table->string('subscription_type_name',255);
          $table->double('subscription_amount', 8, 2);
          $table->string('subscription_validity_type', 255);
          $table->double('subscription_business_rs', 8, 2)->nullable();
          $table->integer('subscription_expected_enquiries')->nullable();
          $table->string('subscription_veh_wheel_type', 255);
          $table->integer('subscription_validity_days');
          $table->date('subscription_validity_from');
          $table->date('subscription_validity_to');
          $table->string('subscription_type_image', 1024)->nullable();
          $table->string('subscription_desc',255)->nullable();
          $table->integer('subscription_bid_advantage')->nullable();
          $table->integer('subscription_priority_scale')->nullable();
          $table->integer('subscription_efficency')->nullable();
          $table->tinyInteger('is_active')->default(0);
          $table->timestamps();
          $table->string('subscription_plan_created_by',255);
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
        Schema::table('subscription_plans', function (Blueprint $table) {
            Schema::dropIfExists('subscription_plans');
        });
    }
}
