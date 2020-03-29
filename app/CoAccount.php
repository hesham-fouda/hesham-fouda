<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class CoAccount
 * @property CoAccountSubscription subscription
 * @property CoAccountSubscriptionDevice devices
 * @package App
 */
class CoAccount extends Model
{
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [ 'phone', 'full_name', 'password' ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscription(){
        return $this->hasOne(CoAccountSubscription::class, 'account_id')->orderByDesc('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function devices(){
        return $this->hasManyThrough(CoAccountSubscriptionDevice::class, CoAccountSubscription::class);
    }
}
