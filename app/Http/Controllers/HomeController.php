<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\AccountTranx;
use App\Models\Bankacc;

class HomeController extends Controller
{
    public function dashboard(){
        activity()->log('Logged in');
        // accounts_payable
        // $banks = Bankacc::get();
        // $accounts_payable_bid = 0;
        // $accounts_receivable_bid = 0;
        // foreach($banks as $r){
        //     if($r->type == 'Due' && $r->name == 'Due')
        //         $accounts_payable_bid = $r->id;
        //     if($r->type == 'Due' && $r->name == 'Due2')
        //         $accounts_receivable_bid = $r->id;
        // }
        
        // $data['accounts_payable'] = AccountTranx::where('account_id', '=', $accounts_payable_bid)
        //                             ->sum('amount');
        // $data['accounts_receivable'] = AccountTranx::where('account_id', '=', $accounts_receivable_bid)
        //                             ->sum('amount');
        $data = array();
        return view('admin.home', compact('data'));
    }
}
