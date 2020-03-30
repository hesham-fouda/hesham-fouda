<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class CoAccount
 * @package App
 * @property int id
 * @property string full_name
 * @property string phone
 * @property string password
 * @property CoAccountSubscription subscription
 * @property CoAccountSubscriptionDevice devices
 */
class CoAccount extends Model
{
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = ['phone', 'full_name', 'password'];

    /**
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscription()
    {
        return $this->hasOne(CoAccountSubscription::class, 'account_id')->orderByDesc('id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function devices()
    {
        return $this->hasManyThrough(CoAccountSubscriptionDevice::class, CoAccountSubscription::class, 'account_id', 'subscription_id');
    }
}
