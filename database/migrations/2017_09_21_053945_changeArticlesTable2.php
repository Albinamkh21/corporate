<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeArticlesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->integer('userId')->unsigned()->default(1);
            $table->foreign('userId')->references('id')->on('users');

            $table->integer('categoryId')->unsigned()->default(1);
            $table->foreign('categoryId')->references('id')->on('articleCategories');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {



            //
        });
    }
}
