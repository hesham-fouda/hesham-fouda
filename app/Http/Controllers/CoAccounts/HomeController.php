<?php

namespace App\Http\Controllers\CoAccounts;

use App\CoAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $accounts = CoAccount::query()->with('devices', 'subscription')->get();
        return view('co_accounts.home', compact([ 'accounts' ]));
    }
}
