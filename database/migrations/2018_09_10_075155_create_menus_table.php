<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('menu_id')->nullable();
            $table->string('name', 128);
            $table->integer('index');
            $table->string('controller', 128)->nullable();
            $table->string('path', 128)->nullable();
            $table->text('callback')->nullable();
            $table->string('icon', 128)->nullable();
            $table->timestamps();
        });
        Schema::disableForeignKeyConstraints();
        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('menu_id')
                    ->references('id')
                    ->on('menus')
                    ->onDelete('cascade')
                    ;
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
