<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluate_data', function (Blueprint $table) {
            $table->increments('id');
            $table->interger('header_id');
            $table->integer('topic_group_id');
            $table->decimal('topic_group_ratio',7,4);
            $table->integer('seq_no');
            $table->text('topic_desc');
            $table->integer('score');
            $table->decimal('ratio_score',7,4);
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
        Schema::dropIfExists('evaluate_data');
    }
}
