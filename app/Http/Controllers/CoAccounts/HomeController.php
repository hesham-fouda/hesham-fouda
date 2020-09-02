<?php

namespace App\Http\Controllers\CoAccounts;

use App\CoAccount;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $accounts = CoAccount::query()->orderBy('id')->with('devices', 'subscription')->get();
        $accounts = $accounts->groupBy(function($account){
            if(is_null($account->subscription))
                return 'no_subscription';
            elseif(!is_null($account->subscription->expire_at) && now()->startOfDay()->greaterThan($account->subscription->expire_at))
                return 'expired';
            else
                return 'valid';
        })->map(function(Collection $accounts){
            return $accounts->sortBy('id');
        })->sortBy(function($val, $key){
            return array_search($key, ['valid', 'no_subscription', 'expired']);
        });

        return view('co_accounts.home', compact([ 'accounts' ]));
    }
}
