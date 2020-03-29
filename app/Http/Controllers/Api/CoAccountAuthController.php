<?php

namespace App\Http\Controllers\Api;

use App\CoAccount;
use App\CoAccountSubscriptionDevice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CoAccountAuthController extends Controller
{
    public function CoLogin(Request $request)
    {
        /** @var Validator $validator * */
        $validator = Validator::make($request->all(), [
            'phone' => 'required|eg_phone_number',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return \response()->json(['errors' => $validator->errors()], 400);
        } else if (!$request->hasCookie('hwd')) {
            return \response()->json([
                'errors' => [
                    'phone' => ['حصل خطأ أثناء تسجيل الدخول !']
                ]
            ], 400);
        } else {

            $coAccount = CoAccount::query()->where('phone', request('phone'))
                ->with(['subscription', 'subscription.devices'])
                ->where('password', request('password'))->limit(1)->get();

            if ($coAccount->count() > 0) {
                $coAccount = $coAccount->first();
                return $this->CoAccountResponse($request, $coAccount);
            } else
                return \response()->json([
                    'errors' => [
                        'phone' => ['بيانات الدخول مش صح !']
                    ]
                ], 400);
        }
    }

    function CoAccountResponse(Request $request, CoAccount $coAccount, bool $newToken = true)
    {
        if (is_null($coAccount->subscription) || (!is_null($coAccount->subscription->expire_at) && now()->greaterThan($coAccount->subscription->expire_at)))
            return \response()->json([
                'errors' => [
                    'phone' => ['مفيش عندك إشتراك تواصل مع الدعم الفنى .']
                ]
            ], 400);

        if ($coAccount->subscription->max_devices < $coAccount->subscription->devices->count())
            return \response()->json([
                'errors' => [
                    'phone' => ['إشتراكك موقوف بسبب عدد الأجهزة راجع الدعم الفنى !']
                ]
            ], 400);

        $device = $coAccount->subscription->devices->filter(function(CoAccountSubscriptionDevice $device) use($request) {
            return $device->device_id === $request->cookie('hwd');
        })->first();

        if (is_null($device))
        {
            $coAccount->subscription->devices()->create([
                'device_id' => $request->cookie('hwd'),
                'device_name' => $request->cookie('dv_name'),
                'last_activity' => now(),
                'ips' => [$request->ip()],
                'token' => Str::random(64),
            ]);
            $coAccount->subscription->refresh();
            $device = $coAccount->subscription->devices->filter(function(CoAccountSubscriptionDevice $device) use($request) {
                return $device->device_id === $request->cookie('hwd');
            })->first();
        } else {
            $updateData = [
                'last_activity' => now(),
                'device_name' => $request->cookie('dv_name'),
            ];
            if(!in_array($request->ip(), ($device->ips ?? [])))
                $updateData['ips'] = array_merge(($device->ips ?? []) + [$request->ip()]);
            if($newToken)
                $updateData['token'] = Str::random(64);

            $device->update($updateData);
        }

        return \response()->json([
            'token' => $coAccount->id . ':' . $device->token,
            'expire_at' => $coAccount->subscription->expire_at ? $coAccount->subscription->expire_at->toFormattedDateString() : null
        ]);
    }
}
