<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->increments('id');

            $table->longText('encryptedLicense')->nullable();
            $table->string('key');
            $table->string('uid');
            $table->string('appName');
            $table->string('serial');
            $table->string('deactivateCode');
            $table->dateTime('generatedDate');
            $table->integer('maxDays')->nullable();
            $table->json('options');
            $table->json('optionsData');
            $table->json('userData')->nullable();
            $table->string('supportId');
            $table->json('features');

            $table->integer('client_id');
            $table->integer('order_id');
            $table->dateTime('deactivate_at')->nullable();
            $table->integer('deactivate_verifier')->nullable();
            $table->dateTime('deactivate_verified')->nullable();
            $table ->softDeletes();
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
        Schema::dropIfExists('licenses');
    }
}
