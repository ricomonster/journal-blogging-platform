<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id');
            $table->string('title');
            $table->text('markdown')->nullable();
            $table->string('slug');
            $table->tinyInteger('status')->default(2)
                ->unsigned();
            $table->tinyInteger('active')->default(1)
                ->unsigned();
            $table->integer('published_at')->unsigned();
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
        Schema::drop('posts');
    }
}
