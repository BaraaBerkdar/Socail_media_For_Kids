<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('childs', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('posts', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('posts', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('category')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('attachments', function(Blueprint $table) {
			$table->foreign('post_id')->references('id')->on('posts')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('category_child', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('category')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('category_child', function(Blueprint $table) {
			$table->foreign('child_id')->references('id')->on('childs')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('record_post', function(Blueprint $table) {
			$table->foreign('child_id')->references('id')->on('childs')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('record_post', function(Blueprint $table) {
			$table->foreign('post_id')->references('id')->on('posts')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('times', function(Blueprint $table) {
			$table->foreign('child_id')->references('id')->on('childs')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('childs', function(Blueprint $table) {
			$table->dropForeign('childs_user_id_foreign');
		});
		Schema::table('posts', function(Blueprint $table) {
			$table->dropForeign('posts_user_id_foreign');
		});
		Schema::table('posts', function(Blueprint $table) {
			$table->dropForeign('posts_category_id_foreign');
		});
		Schema::table('attachments', function(Blueprint $table) {
			$table->dropForeign('attachments_post_id_foreign');
		});
		Schema::table('category_child', function(Blueprint $table) {
			$table->dropForeign('category_child_category_id_foreign');
		});
		Schema::table('category_child', function(Blueprint $table) {
			$table->dropForeign('category_child_child_id_foreign');
		});
		Schema::table('record_post', function(Blueprint $table) {
			$table->dropForeign('record_post_child_id_foreign');
		});
		Schema::table('record_post', function(Blueprint $table) {
			$table->dropForeign('record_post_post_id_foreign');
		});
		Schema::table('times', function(Blueprint $table) {
			$table->dropForeign('times_child_id_foreign');
		});
	}
}