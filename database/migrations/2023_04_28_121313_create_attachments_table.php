<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttachmentsTable extends Migration {

	public function up()
	{
		Schema::create('attachments', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->integer('post_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('attachments');
	}
}