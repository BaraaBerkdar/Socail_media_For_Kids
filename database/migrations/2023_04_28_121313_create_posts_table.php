<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration {

	public function up()
	{
		Schema::create('posts', function(Blueprint $table) {
			$table->increments('id');
			$table->string('caption', 100);
			$table->integer('user_id')->unsigned();
			$table->datetime('date');
			$table->tinyInteger('status')->default('0');
			$table->integer('category_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('posts');
	}
}