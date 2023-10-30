<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChildsTable extends Migration {

	public function up()
	{
		Schema::create('childs', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->string('email', 50);
			$table->string('password', 10);
			$table->string('gender', 10);
			$table->integer('user_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('childs');
	}
}