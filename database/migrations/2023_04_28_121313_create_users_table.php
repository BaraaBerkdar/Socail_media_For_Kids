<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->string('email', 50);
			$table->string('phone_number', 10);
			$table->string('password', 10);
			$table->string('gender', 10);
			$table->string('ssn', 11);
			$table->tinyInteger('role_id')->default('0');
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}