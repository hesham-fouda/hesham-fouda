<?php

namespace App\Http\Controllers\CoAccounts;

use App\CoAccount;
use App\CoAccountSubscription;
use App\CoAccountSubscriptionDevice;
use App\CoAccountSubscriptionLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('subscriptionDeviceDelete');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|min:4',
            'phone' => 'required|eg_phone_number|unique:co_accounts',
            'password' => 'required|confirmed|min:4',
        ]);
        $account = new CoAccount($request->only(['full_name', 'phone', 'password']));
        $account->save();
        return redirect()->route('co_accounts.account.view', $account);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @param CoAccount $CoAccount
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function view(Request $request, CoAccount $CoAccount)
    {
        $devices = $CoAccount->subscription ? $CoAccount->subscription->devices()->orderByDesc('deleted_at')->orderBy('id')
            ->withTrashed()->get() : collect([]);
        return view('co_accounts.account.view', [
            'devices' => $devices,
            'account' => $CoAccount
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @param CoAccount $CoAccount
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function subscriptionStore(Request $request, CoAccount $CoAccount)
    {
        if ($CoAccount->subscription)
            return redirect()->route('co_accounts.account.view', $CoAccount)->with([
                'error' => 'يوجد إشتراك لا يمكن إضافة إشتراك جديد'
            ]);

        $request->validate([
            'max_devices' => 'required|numeric|min:1|max:20',
            'period' => 'required|in:day,week,2week,3week,month',
        ]);


        if ($request->input('period') === 'month')
            $expire_at = now()->addMonths(1)->addDays(-1);
        else
            $expire_at = now()->addDays([
                    'week' => 6,
                    '2week' => 13,
                    '3week' => 20,
                ][$request->input('period')] ?? 0);

        $CoAccount->subscription()->save(new CoAccountSubscription($request->only(['max_devices']) + [
                'start_at' => now(),
                'expire_at' => $expire_at
            ]));
        $CoAccount->refresh();
        CoAccountSubscriptionLogger::query()->create([
            'user_id' => Auth::id(),
            'subscription_id' => $CoAccount->subscription->id,
            'account_id' => $CoAccount->id,
            'type' => 'new',
            'period' => $request->input('period'),
            'devices' => $request->input('max_devices'),
            'created_at' => now()
        ]);

        return redirect()->route('co_accounts.account.view', $CoAccount)->with([
            'status' => 'تم إضافة الإشتراك بنجاح'
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @param CoAccount $CoAccount
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function subscriptionDelete(Request $request, CoAccount $CoAccount)
    {
        if (is_null($CoAccount->subscription))
            return back()->with('error', 'لا يوجد إشتراك لحذفه !');
        $CoAccount->subscription->delete();
        CoAccountSubscriptionLogger::query()->create([
            'user_id' => Auth::id(),
            'subscription_id' => $CoAccount->subscription->id,
            'account_id' => $CoAccount->id,
            'type' => 'delete',
            'created_at' => now()
        ]);
        return back()->with('status', 'تم حذف الإشتراك بنجاح');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @param CoAccount $CoAccount
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function subscriptionUpdate(Request $request, CoAccount $CoAccount)
    {
        if (!$CoAccount->subscription)
            return redirect()->route('co_accounts.account.view', $CoAccount)->with([
                'error' => 'لا يوجد إشتراك لتمديده او تجديده'
            ]);

        $request->validate([
            'period' => 'required|in:day,week,2week,3week,month',
        ]);
        $old_expire_at = $CoAccount->subscription->expire_at;

        if ((!is_null($CoAccount->subscription->expire_at) &&
            now()->startOfDay()->greaterThan($CoAccount->subscription->expire_at))) {
            $type = 'renew';
            $from = now();
        } else {
            $type = 'update';
            $from = $CoAccount->subscription->expire_at;
        }

        if ($type === 'update') {
            if ($request->input('period') === 'month')
                $expire_at = $from->clone()->addMonths(1);
            else
                $expire_at = $from->clone()->addDays([
                        'day' => 1,
                        'week' => 7,
                        '2week' => 14,
                        '3week' => 21,
                    ][$request->input('period')] ?? 0);
        } else {
            if ($request->input('period') === 'month')
                $expire_at = $from->clone()->addMonths(1)->addDays(-1);
            else
                $expire_at = $from->clone()->addDays([
                        'week' => 6,
                        '2week' => 13,
                        '3week' => 20,
                    ][$request->input('period')] ?? 0);
        }

        $CoAccount->subscription->update([
            'start_at' => $from->toDate(),
            'expire_at' => $expire_at->toDate()
        ]);

        $CoAccount->refresh();
        CoAccountSubscriptionLogger::query()->create([
            'user_id' => Auth::id(),
            'subscription_id' => $CoAccount->subscription->id,
            'account_id' => $CoAccount->id,
            'type' => $type,
            'period' => $request->input('period'),
            'old_expire_at' => $old_expire_at,
            'expire_at' => $expire_at,
            'created_at' => now()
        ]);

        return redirect()->route('co_accounts.account.view', $CoAccount)->with([
            'status' => 'تم تمديد / تجديد الإشتراك بنجاح'
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @param CoAccount $CoAccount
     * @param CoAccountSubscription $CoAccountSubscription
     * @param CoAccountSubscriptionDevice $CoAccountSubscriptionDevice
     * @return \Illuminate\Contracts\Support\Renderable
     * @throws \Exception
     */
    public function subscriptionDeviceDelete(Request $request, CoAccount $CoAccount,
                                             CoAccountSubscription $CoAccountSubscription,
                                             CoAccountSubscriptionDevice $CoAccountSubscriptionDevice)
    {
        if ($CoAccountSubscriptionDevice->subscription_id !== $CoAccountSubscription->id)
            return redirect()->route('co_accounts.account.view', $CoAccount)->with([
                'error' => 'خطأ فى الرابط يرجى إعادة المحاولة'
            ]);

        $CoAccountSubscriptionDevice->delete();
        return redirect()->route('co_accounts.account.view', $CoAccount)->with([
            'status' => 'تم حذف الجهاز بنجاح'
        ]);
    }
}
