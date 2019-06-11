<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
             $table->increments('id');
            $table->string('person_code');
            $table->string('person_full_name');
            $table->string('person_title');
            $table->string('person_name');
            $table->string('person_surname');  
            $table->string('person_full_name_en');          
            $table->string('person_title_en');
            $table->string('person_name_en');
            $table->string('person_surname_en');
            $table->date('date_of_work');
            $table->string('image');
            $table->string('position_name');
            $table->integer('position_rank_id');
            $table->integer('section_id');
            $table->integer('department_id');
            $table->integer('evaluator_id');
            $table->integer('evaluator_2_id');
            $table->integer('evaluator_3_id');

            $table->tinyInteger('status');
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
        Schema::dropIfExists('employees');
    }
}
