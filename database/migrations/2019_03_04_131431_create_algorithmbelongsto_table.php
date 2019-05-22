<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlgorithmbelongstoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('algorithmbelongsto', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
                $table->foreign('user_id')->unsigned()->references('id')->on('users')->onDelete('cascade');

            $table->integer('algorithm_id')->unsigned()->index();
                $table->foreign('algorithm_id')->unsigned()->references('id')->on('algorithm')->onDelete('cascade');

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
        Schema::dropIfExists('algorithmbelongsto');
    }
}
