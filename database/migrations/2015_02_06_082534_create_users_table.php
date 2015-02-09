<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('password');
			$table->string('name');
			$table->string('biography')->nullable();
			$table->string('website')->nullable();
			$table->string('location')->nullable();
			$table->string('avatar_url')->nullable();
			$table->string('cover_url')->nullable();
			$table->tinyInteger('role')->default(2)->unsigned();
			$table->tinyInteger('active')->default(1)->unsigned();
			$table->string('slug');
			$table->dateTime('login_at');
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
