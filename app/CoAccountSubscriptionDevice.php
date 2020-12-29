<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * Class CoAccountSubscriptionDevice
 * @package App
 * @property int id
 * @property int subscription_id
 * @property string device_id
 * @property string device_name
 * @property string app_version
 * @property string token
 * @property array|null ips
 * @property Carbon last_activity
 */
class CoAccountSubscriptionDevice extends Model /*implements \OwenIt\Auditing\Contracts\Auditable*/
{
    use SoftDeletes;
    ///use Auditable;

    protected $auditExclude = ['last_activity', 'ips', 'token'];

    protected $fillable = [ 'subscription_id', 'device_id', 'device_name', 'app_version', 'token', 'ips', 'last_activity' ];

    protected $casts = [
        'ips' => 'array',
        'subscription_id' => 'int',
        'last_activity' => 'datetime',
    ];

    public function subscription(){
        return $this->belongsTo(CoAccountSubscription::class, 'subscription_id');
    }
}
