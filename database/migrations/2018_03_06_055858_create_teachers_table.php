<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->string('name', 20)->comment('姓名');
            $table->string('name_py', 20)->comment('姓名拼音');
            $table->enum('sex', ['男', '女'])->default('男')->comment('性别');
            $table->integer('staffroom_id')->comment('教研室id');
            $table->integer('joblevel_id')->comment('职务级别');
            $table->integer('title_id')->comment('职称');
            $table->integer('jobtype_id')->comment('职务类型');
            $table->timestamps();
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
        Schema::dropIfExists('teachers');
    }
}
