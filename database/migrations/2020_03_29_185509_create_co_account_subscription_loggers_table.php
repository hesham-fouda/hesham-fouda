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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('subscription_id');
            $table->string('devices')->nullable();
            $table->string('period')->nullable();
            $table->enum('type', ['new', 'update', 'renew']);
            $table->string('note')->nullable();
            $table->date('old_expire_at')->nullable();
            $table->date('expire_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::table('co_account_subscription_loggers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('account_id')->references('id')->on('co_account_subscriptions')->onDelete('cascade');
            $table->foreign('subscription_id')->references('id')->on('co_accounts')->onDelete('cascade');
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

        Schema::table('co_account_subscription_loggers', function (Blueprint $table) {
            $table->dropForeign('user_id');
            $table->dropForeign('account_id');
            $table->dropForeign('subscription_id');
        });
    }
}
