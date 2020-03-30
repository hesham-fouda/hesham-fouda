<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoAccountSubscriptionDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_account_subscription_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subscription_id');
            $table->string('device_id');
            $table->string('device_name');
            $table->string('token');
            $table->json('ips');
            $table->dateTimeTz('last_activity');
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_account_subscription_devices');
    }
}
