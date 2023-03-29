<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditDebitDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ggt_op_credit_debit_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('note_type', 50)->nullable();
            $table->string('op_uid', 255);
            $table->string('op_mobile_no', 20);
            $table->string('amount', 100);
            $table->string('reason', 100)->nullable();
            $table->string('reference', 100)->nullable();
            $table->string('status', 50)->nullable();
            $table->string('party_id', 50)->nullable();
            $table->string('transaction_id', 50)->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->date('approval_date')->nullable();
            $table->integer('created_admin_id')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('approved_by', 100)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('ggt_op_credit_debit_details');
    }
}
