<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluate_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('header_id');
            $table->integer('topic_group_id');
            $table->integer('topic_group_ratio');
            $table->integer('seq_no');
            $table->string('topic_name');
            $table->string('topic_desc');
            $table->integer('score');
            $table->decimal('ratio_score',7,4);
            $table->integer('score2');
            $table->decimal('ratio_score2',7,4);
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
        Schema::dropIfExists('evaluate_details');
    }
}
