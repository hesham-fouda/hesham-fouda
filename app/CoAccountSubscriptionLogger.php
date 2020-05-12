<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoAccountSubscriptionLogger extends Model
{
    protected $fillable = [ 'subscription_id', 'note', 'account_id', 'devices', 'period', 'type', 'created_at' ];

    protected $dates = ['created_at'];

    public $timestamps = false;
}
