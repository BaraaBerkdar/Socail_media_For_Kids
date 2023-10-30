<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecordPostTable extends Migration {

	public function up()
	{
		Schema::create('record_post', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('child_id')->unsigned();
			$table->integer('post_id')->unsigned();
			$table->string('comments', 255)->nullable();
			$table->string('reaction', 20)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('record_post');
	}
}