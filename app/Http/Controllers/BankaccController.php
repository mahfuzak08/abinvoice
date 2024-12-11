<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Bankacc;
use App\Models\AccountTranx;

class BankaccController extends Controller
{
    public function index(){
        $banks = Bankacc::all();
        return view('admin.bank.manage', compact('banks'));
    }
    
    public function open_account_form(){
        return view('admin.bank.addnew');
    }
    
    /**
     * Validate and create a new account.
     * Add first transection 0 or opening balance
     *
     */
    public function set_account(Request $request){
        // print_r($request->all());
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:255']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('add_new_account');
        }

        $data = new Bankacc();
        
        // echo "<hr>";
        $input = [ "name"=>$request->input('name'), "type"=>$request->input('type'), "currency"=>$request->input('currency')];
        if(! empty($request->input('bank_name'))) array_push($input, $request->input('bank_name'));
        if(! empty($request->input('bank_address'))) array_push($input, $request->input('bank_address'));
        if(! empty($request->input('acc_no'))) array_push($input, $request->input('acc_no'));
        // print_r($input);
        $data->fill($input)->save();
        if(!empty($request->input('opening_balance')) && $request->input('opening_balance')>0){
            $accid = $data->id;
            $trnxdata = [
                'account_id' => $accid,
                'user_id' => Auth::id(),
                'tranx_date' => date("Y-m-d"),
                'ref_type' => 'Opening Balance',
                'amount' => $request->input('opening_balance')
            ];
            $tdata = new AccountTranx();
            $tdata->fill($trnxdata)->save();
        }
        flash()->addSuccess('New Data Added Successfully.');
        return redirect('bank_account');
    }

    public function edit_account($id){
        $bank = Bankacc::findOrFail($id);
        return view('admin.bank.edit', compact('bank'));
    }
    
    public function update_account(Request $request, $id){
        // print_r($request->all());
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:255']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('edit_account', $id);
        }

        $data = Bankacc::findOrFail($id);
        
        // echo "<hr>";
        $input = [ "name"=>$request->input('name'), "type"=>$request->input('type'), "currency"=>$request->input('currency')];
        if(! empty($request->input('bank_name'))) array_push($input, $request->input('bank_name'));
        if(! empty($request->input('bank_address'))) array_push($input, $request->input('bank_address'));
        if(! empty($request->input('acc_no'))) array_push($input, $request->input('acc_no'));
        // print_r($input);
        $data->update($input);
        flash()->addSuccess('Data Updated Successfully.');
        return redirect('bank_account');
    }

    public function delete_account($id){
        $data = Bankacc::findOrFail($id);
        $data->delete();
        flash()->addSuccess('Data Delete Successfully.');
        return redirect('bank_account');
    }

    public function acc_details($id){
        $bank = Bankacc::findOrFail($id);
        $balance = AccountTranx::where('account_id', $id)->sum('amount');
        if(! empty(request()->input('search'))){
            $str = request()->input('search');
            $datas = AccountTranx::where(function ($query) use ($str){
                                $query->where('tranx_date', 'like', '%'.$str.'%')
                                ->orWhere('ref_id', 'like', '%'.$str.'%')
                                ->orWhere('ref_type', 'like', '%'.$str.'%')
                                ->orWhere('amount', 'like', '%'.$str.'%')
                                ->orWhere('note', 'like', '%'.$str.'%');
                            })
                            ->where('account_id', $id)
                            ->latest()->paginate(10)->withQueryString();
        }else{
            $datas = AccountTranx::where('account_id', $id)->latest()->paginate(10)->withQueryString();
        }
        return view('admin.bank.details', compact('bank', 'balance', 'datas'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function add_amount(Request $request){
        $rules = [
            'account_id' => ['required'],
            'tranx_date' => ['required', 'date'],
            'amount' => ['required', 'numeric']
        ];
        $id = $request->input('account_id');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('account_details/'.$id);
        }

        $data = new AccountTranx();
        
        $input = $request->all();
        $input['user_id'] = Auth::id();
        if($input['type'] == 'withdrawal') $input['amount'] *= -1;
        if(empty($input['note'])) $input['note'] = 'Direct '.$input['type'];
        
        $data->fill($input)->save();
        flash()->addSuccess('New Data Added Successfully.');

        return redirect('account_details/'.$id);
    }

    public function fund_transfer_form(){
        $banks = Bankacc::all();
        $data = AccountTranx::join('bankaccs', 'account_tranxes.account_id', '=', 'bankaccs.id')
                            ->where('ref_type', 'fund_transfer')
                            ->select('account_tranxes.*', 'bankaccs.name as bank_name')
                            ->latest()->paginate(10)->withQueryString();
        return view('admin.bank.fund', compact('banks', 'data'));
    }
    
    public function transfering(Request $request){
        $rules = [
            'date' => ['required', 'date'],
            'from_acc' => ['required', 'numeric'],
            'to_acc' => ['required', 'numeric'],
            'amount' => ['required', 'numeric']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
        }else{
            $available_balance = AccountTranx::where("account_id", $request->input('from_acc'))->sum('amount');
            // dd($available_balance . " == " .$request->input('amount'));
            if($available_balance>=$request->input('amount')){
                DB::beginTransaction();
                $trnxdata = [
                    'account_id' => $request->input('from_acc'),
                    'user_id' => Auth::id(),
                    'tranx_date' => $request->input('date'),
                    'ref_id' => time(),
                    'ref_type' => 'fund_transfer',
                    'amount' => $request->input('amount') * -1,
                    'note' => 'from',
                    'ref_tranx_id' => $request->input('to_acc'),
                    'ref_tranx_type' => 'Pending'
                ];
                $tdata = new AccountTranx();
                $tdata->fill($trnxdata)->save();
                flash()->addSuccess('Balance transfering...');
                DB::commit();
            }else{
                flash()->addError('Insufficient Balance');
            }
        }
        return redirect('fund_transfer');
    }
    
    public function transfer_action($type, $id){
        if($type == 'accept'){
            $data = AccountTranx::findOrFail($id);
            if($data->ref_tranx_type == 'Pending'){
                $data->ref_tranx_type = 'Accepted';
                try{
                    DB::beginTransaction();
                    $trnxdata = [
                        'account_id' => $data->ref_tranx_id,
                        'user_id' => Auth::id(),
                        'tranx_date' => date("Y-m-d"),
                        'ref_id' => $data->ref_id,
                        'ref_type' => 'fund_transfer',
                        'amount' => $data->amount * -1,
                        'note' => 'to',
                        'ref_tranx_id' => $data->account_id,
                        'ref_tranx_type' => 'Received'
                    ];
                    $tdata = new AccountTranx();
                    $tdata->fill($trnxdata)->save();

                    $data->save();
                    flash()->addSuccess('Fund Received Successfully.');

                    DB::commit();
                }catch (\Exception $e) {
                    // If any query fails, catch the exception and roll back the transaction
                    dd($e);
                    flash()->addError('Fund Received Not Added Successfully.');
                    DB::rollback();
                }
            }
        }
        elseif($type == 'reject'){
            $data = AccountTranx::findOrFail($id);
            $data->ref_tranx_type = 'Rejected';
            try{
                DB::beginTransaction();
                $trnxdata = [
                    'account_id' => $data->account_id,
                    'user_id' => Auth::id(),
                    'tranx_date' => date("Y-m-d"),
                    'ref_id' => $data->ref_id,
                    'ref_type' => 'fund_transfer',
                    'amount' => $data->amount * -1,
                    'note' => 'No payment received',
                    'ref_tranx_id' => $data->ref_tranx_id,
                    'ref_tranx_type' => 'Rejected'
                ];
                $tdata = new AccountTranx();
                $tdata->fill($trnxdata)->save();

                $data->save();
                flash()->addSuccess('Fund Rejected Successfully.');

                DB::commit();
            }catch (\Exception $e) {
                // If any query fails, catch the exception and roll back the transaction
                dd($e);
                flash()->addError('Fund Rejected Not Added Successfully.');
                DB::rollback();
            }
        }
        elseif($type == 'delete'){
            $data = AccountTranx::findOrFail($id);
            $dd = AccountTranx::where('ref_id', $data->ref_id)->where('ref_type', 'fund_transfer');
            $dd->delete();
            flash()->addSuccess('Data Delete Successfully.');
        }
        return redirect('fund_transfer');
    }
}
