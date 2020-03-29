<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoAccountSubscriptionDevice extends Model
{
    use SoftDeletes;
    protected $fillable = [ 'subscription_id', 'device_id', 'device_name', 'token', 'ips', 'last_activity' ];

    protected $casts = [
        'ips' => 'array',
        'last_activity' => 'datetime',
    ];

    public function subscription(){
        return $this->belongsTo(CoAccountSubscription::class, 'subscription_id');
    }
}
