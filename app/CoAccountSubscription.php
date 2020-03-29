<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoAccountSubscription extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'account_id', 'max_devices', 'start_at', 'expire_at' ];

    protected $casts = [
        'start_at' => 'datetime',
        'expire_at' => 'datetime'
    ];

    public function account(){
        return $this->belongsTo(CoAccount::class);
    }

    public function devices(){
        return $this->hasMany(CoAccountSubscriptionDevice::class, 'subscription_id');
    }
}
