<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoryTable extends Migration {

	public function up()
	{
		Schema::create('category', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 50);
			$table->string('descreption', 255);
		});
	}

	public function down()
	{
		Schema::drop('category');
	}
}