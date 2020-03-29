<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoAccountSubscriptionLogger extends Model
{
    protected $fillable = [ 'subscription_id', 'note' ];

    protected $dates = ['created_at'];
}
