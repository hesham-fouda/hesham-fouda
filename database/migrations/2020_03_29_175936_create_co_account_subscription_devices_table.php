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
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->string('device_id');
            $table->string('device_name');
            $table->string('app_version')->nullable();
            $table->string('token');
            $table->json('ips');
            $table->dateTime('last_activity');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('co_account_subscription_devices', function (Blueprint $table) {
            $table->foreign('subscription_id')->references('id')->on('co_account_subscriptions')->onDelete('set null');
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

        Schema::table('co_account_subscription_devices', function (Blueprint $table) {
            $table->dropForeign('subscription_id');
        });
    }
}
