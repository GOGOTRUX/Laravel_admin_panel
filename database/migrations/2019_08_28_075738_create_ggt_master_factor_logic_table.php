<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGgtMasterFactorLogicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ggt_master_factor_logic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factor_id');
            $table->string('logic_id', 50)->nullable();
            $table->string('variable_name', 500)->nullable();
            $table->string('variable_value', 500)->nullable();
            $table->decimal('existing_value', 8, 2)->default(0);
            $table->decimal('new_value', 8, 2)->nullable();
            $table->date('revision_date')->nullable();
            $table->integer('created_by');
            $table->tinyInteger('is_sent_for_approval')->default(0);
            $table->date('approval_date')->nullable();
            $table->string('approved_by', 100)->nullable();
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
        Schema::dropIfExists('ggt_master_factor_logic');
    }
}
