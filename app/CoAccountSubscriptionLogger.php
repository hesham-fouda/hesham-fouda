<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoAccountSubscriptionLogger extends Model
{
    protected $fillable = [ 'user_id', 'subscription_id', 'note', 'account_id', 'devices', 'period', 'type', 'old_expire_at', 'expire_at', 'created_at' ];

    protected $dates = ['created_at'];

    protected $casts = [
        'user_id' => 'int',
        'subscription_id' => 'int',
        'account_id' => 'int',
        'old_expire_at' => 'datetime',
        'expire_at' => 'datetime',
    ];

    public $timestamps = false;
}
