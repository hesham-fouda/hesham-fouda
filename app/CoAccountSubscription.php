<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use OwenIt\Auditing\Auditable;

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
class CoAccountSubscription extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use SoftDeletes, Auditable;

    /**
     * Auditable events.
     *
     * @var array
     */
    protected $auditEvents = [
        'updated',
    ];

    protected $fillable = [ 'account_id', 'max_devices', 'start_at', 'expire_at' ];

    protected $casts = [
        'account_id' => 'int',
        'max_devices' => 'int',
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
