<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoryChildTable extends Migration {

	public function up()
	{
		Schema::create('category_child', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('category_id')->unsigned();
			$table->integer('child_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('category_child');
	}
}