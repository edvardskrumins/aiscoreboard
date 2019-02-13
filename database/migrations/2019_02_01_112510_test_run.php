<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TestRun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("test_run", function(Blueprint $table) {
            $table->increments("id");
            $table->enum("status", ["building", "running", "failed", "success"]);
            $table->double("score");
            $table->longText("info");
            $table->unsignedInteger("test_data_id");
            $table->unsignedInteger("algorithm_id");
            $table->foreign("test_data_id")->references("id")->on("test_data")->onDelete("cascade");
            $table->foreign("algorithm_id")->references("id")->on("algorithm")->onDelete("cascade");
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
        Schema::dropIfExists("test_run");
    }
}
