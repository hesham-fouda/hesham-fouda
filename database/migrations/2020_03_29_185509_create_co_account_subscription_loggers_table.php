<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoAccountSubscriptionLoggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_account_subscription_loggers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subscription_id');
            $table->string('note');
            $table->timestampTz('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_account_subscription_loggers');
    }
}
