<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCommentsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {

            $table->integer('userId')->unsigned()->nullable();
            $table->foreign('userId')->references('id')->on('users');

            $table->integer('articleId')->unsigned()->default(1);
            $table->foreign('articleId')->references('id')->on('articles');

            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {


        });
    }
}
