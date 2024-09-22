<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('activities');
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            //Set Logged User
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_type')->nullable();

            //User Request
            $table->string('request_hash')->index();
            $table->string('http_version')->nullable();
            $table->double('response_time')->nullable();
            $table->integer('status')->nullable();
            $table->string('method')->nullable();
            $table->string('url')->nullable();
            $table->string('referer')->nullable();
            $table->json('query')->nullable();

            //User Agent
            $table->string('remote_address')->nullable();
            $table->text('user_agent')->nullable();

            //Response
            $table->json('response')->nullable();

            //Log Level
            $table->string('level')->default('info')->nullable();

            //User
            $table->string('user')->nullable();

            //Activity Log
            $table->json('log')->nullable();

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
        Schema::dropIfExists('activities');
    }
};
