<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimesTable extends Migration {

	public function up()
	{
		Schema::create('times', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('child_id')->unsigned();
			$table->date('day');
			$table->time('time');
		});
	}

	public function down()
	{
		Schema::drop('times');
	}
}