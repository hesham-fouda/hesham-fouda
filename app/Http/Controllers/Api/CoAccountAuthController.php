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

/**
 * Class CoAccountAuthController
 * @package App\Http\Controllers\Api
 */
class CoAccountAuthController extends Controller
{
    public $isV2 = false;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CoLogin(Request $request)
    {
        /** @var Validator $validator * */
        $validator = Validator::make($request->all(), [
            'phone' => 'required|eg_phone_number',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return \response()->json(['errors' => $validator->errors()], 400);
        } else if (!$request->{$this->isV2 ? 'has' : 'hasCookie'}('hwd')) {
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

    /**
     * Handle check login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function CoLoginCheck(Request $request)
    {
        /** @var Validator $validator * */
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return \response()->json(['errors' => $validator->errors()], 400);
        } else if (!$request->{$this->isV2 ? 'has' : 'hasCookie'}('hwd')) {
            return \response()->json([
                'errors' => [
                    'phone' => ['حصل خطأ أثناء التحقق من تسجيل الدخول !']
                ]
            ], 400);
        } else {
            try {
                $token = explode(':', $request->post('token'));
                $coAccount = CoAccount::query()->where('id', $token[0])
                    ->whereHas('devices', function (Builder $query) use ($token, $request) {
                        $query->where('token', $token[1])->where('device_id', str_replace(' ', '+', $request->{$this->isV2 ? 'input' : 'cookie'}('hwd')));
                    })->limit(1)->get();


                /*dd(
                    $coAccount,
                    str_replace(' ', '+', $request->cookie('hwd'))
                );*/
                if ($coAccount->count() > 0) {
                    $coAccount = $coAccount->first();
                    return $this->CoAccountResponse($request, $coAccount, false);
                } else
                    return \response()->json([
                        'errors' => [
                            'phone' => ['خطأ !']
                        ]
                    ], 400);
            } catch (\Exception $ex) {
                return \response()->json([
                    'errors' => [
                        $ex->getMessage()
                    ]
                ], 400);
            }

        }
    }

    /**
     * @param Request $request
     * @param CoAccount $coAccount
     * @param bool $newToken
     * @return \Illuminate\Http\JsonResponse
     */
    function CoAccountResponse(Request $request, CoAccount $coAccount, bool $newToken = true)
    {
        if (is_null($coAccount->subscription))
            return \response()->json([
                'errors' => [
                    'phone' => ['مفيش عندك إشتراك كلم الدعم الفنى .']
                ]
            ], 400);

        if ((!is_null($coAccount->subscription->expire_at) && now()->startOfDay()->greaterThan($coAccount->subscription->expire_at)))
            return \response()->json([
                'errors' => [
                    'phone' => ['إشتراكك خلصان كلم الدعم الفنى .']
                ]
            ], 400);

        if ($coAccount->subscription->max_devices < $coAccount->subscription->devices->count())
            return \response()->json([
                'errors' => [
                    'phone' => ['إشتراكك موقوف بسبب عدد الأجهزة راجع الدعم الفنى !']
                ]
            ], 400);

        /** @var CoAccountSubscriptionDevice $device * */
        $device = $coAccount->subscription->devices->filter(function (CoAccountSubscriptionDevice $device) use ($request) {
            return $device->device_id === str_replace(' ', '+', $request->{$this->isV2 ? 'input' : 'cookie'}('hwd'));
        })->first();

        if (is_null($device)) {
            if ($coAccount->subscription->max_devices >= $coAccount->subscription->devices->count())
                return \response()->json([
                    'errors' => [
                        'phone' => ['وصلت لأقصى حد فى عدد الأجهزة مينفعش تستعمل أجهزة أكتر من كدا !']
                    ]
                ], 400);

            $coAccount->subscription->devices()->create([
                'device_id' => str_replace(' ', '+', $request->{$this->isV2 ? 'input' : 'cookie'}('hwd')),
                'device_name' => $request->{$this->isV2 ? 'input' : 'cookie'}('dv_name'),
                'last_activity' => now(),
                'ips' => [$request->ip()],
                'token' => Str::random(64),
            ]);
            $coAccount->subscription->refresh();
            $device = $coAccount->subscription->devices->filter(function (CoAccountSubscriptionDevice $device) use ($request) {
                return $device->device_id === str_replace(' ', '+', $request->{$this->isV2 ? 'input' : 'cookie'}('hwd'));
            })->first();
        } else {
            $updateData = [
                'last_activity' => now(),
                //'device_name' => $request->{$this->isV2 ? 'input' : 'cookie'}('dv_name'),
            ];
            $dv_name = $request->{$this->isV2 ? 'input' : 'cookie'}('dv_name');
            if ($dv_name !== $device->device_name)
                $updateData['device_name'] = $dv_name;

            $app_version = $request->{$this->isV2 ? 'input' : 'cookie'}('app_version');
            if ($app_version !== null && $app_version !== $device->app_version)
                $updateData['app_version'] = $app_version;

            if (!in_array($request->ip(), ($device->ips ?? [])))
                $updateData['ips'] = array_merge(($device->ips ?? []), [$request->ip()]);

            if ($newToken)
                $updateData['token'] = Str::random(64);

            $device->update($updateData);
        }

        return \response()->json([
            'token' => $coAccount->id . ':' . $device->token,
            'expire_at' => $coAccount->subscription->expire_at ? $coAccount->subscription->expire_at->toFormattedDateString() : null
        ]);
    }
}
