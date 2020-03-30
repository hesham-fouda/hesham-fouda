<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Class CoAccountSubscription
 * @package App
 * @property int id
 * @property int account_id
 * @property int max_devices
 * @property Carbon start_at
 * @property Carbon expire_at
 * @property Collection devices
 * @property CoAccount account
 */
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
