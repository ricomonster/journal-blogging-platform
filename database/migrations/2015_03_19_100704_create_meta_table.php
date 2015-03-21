<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('table', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('parent_id')->unsigned();
            $table->string('meta_type');
            $table->string('meta_name');
            $table->string('meta_value');
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
		Schema::drop('table');
	}
}
