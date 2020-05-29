<?php

namespace App\Http\Controllers\Api;

use App\CoAccount;
use App\CoAccountSubscriptionDevice;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Parent_;

/**
 * Class CoAccountAuthController
 * @package App\Http\Controllers\Api
 */
class CoAccountAuthControllerV2 extends CoAccountAuthController
{

    /**
     * CoAccountAuthControllerV2 constructor.
     */
    public function __construct()
    {
        $this->isV2 = true;
    }
}
