<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SendSms;
use App\Models\AccountTranx;
use App\Models\Bankacc;
use App\Models\Customer;
use App\Models\Sales;

class CustomerController extends Controller
{
    public function index(){
        $ctotal = [];
        $banks = Bankacc::get();
        $accounts_payable_bid = 0;
        $cash_bid = 0;
        foreach($banks as $r){
            if($r->type == 'Due' && $r->name == 'Due')
                $accounts_payable_bid = $r->id;
            if($r->type == 'Cash' && $r->name == 'Cash')
                $cash_bid = $r->id;
        }
        if(! empty(request()->input('search'))){
            $str = request()->input('search');
            $datas = Customer::select('customers.*')
                            ->addSelect(DB::raw('(COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE account_id = "'.$cash_bid.'" AND ref_id = customers.id AND ref_type = "customer"), 0) - COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE account_id = "'.$accounts_payable_bid.'" AND ref_id = customers.id AND ref_type = "customer"), 0)) as due'))
                            ->where(function ($query) use ($str){
                                $query->where('name', 'like', '%'.$str.'%')
                                ->orWhere('mobile', 'like', '%'.$str.'%')
                                ->orWhere('email', 'like', '%'.$str.'%')
                                ->orWhere('address', 'like', '%'.$str.'%');
                            })
                            ->where('is_delete', 0)
                            ->latest()->paginate(50)->withQueryString();
        }else{
            $datas = Customer::select('customers.*')
                            ->addSelect(DB::raw('(COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE account_id = "'.$cash_bid.'" AND ref_id = customers.id AND ref_type = "customer"), 0) - COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE account_id = "'.$accounts_payable_bid.'" AND ref_id = customers.id AND ref_type = "customer"), 0)) as due'))
                            ->latest()
                            ->where('is_delete', 0)
                            ->paginate(50)
                            ->withQueryString();
        }
        
        return view('admin.customer.manage', compact('datas', 'ctotal'))->with('i', (request()->input('page', 1) - 1) * 50);
    }

    public function open_customer_form(){
        return view('admin.customer.addnew');
    }

    public function set_customer(Request $request){
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits:11', 'unique:customers,mobile']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('add_new_customer');
        }
        
        DB::beginTransaction();
        try{
            $data = new Customer();
            
            $input = $request->all();
            if($input['mobile']) $input['mobile'] = b2en($input['mobile']);
            if($input['balance']) $input['balance'] = b2en($input['balance']);
            $input['balance'] = empty($input['balance']) || $input['balance'] == null ? 0 : $input['balance'];
            $input['opening_balance'] = $input['balance'];
            $data->fill($input)->save();
    
            // $customer_id = $data->id;
            
            // $due_acc_id = Bankacc::where('type', 'Cash')->pluck('id');
            // $trnxdata = [
            //     'account_id' => $due_acc_id[0],
            //     'user_id' => Auth::id(),
            //     'tranx_date' => date("Y-m-d"),
            //     'ref_id' => $customer_id,
            //     'ref_type' => 'customer',
            //     'note' => 'Customer Opening Due Balance',
            //     'amount' => !empty($request->input('balance')) && $request->input('balance')>0 ? ($request->input('balance') * -1) : 0
            // ];
            // $tdata = new AccountTranx();
            // $tdata->fill($trnxdata)->save();
            
            flash()->addSuccess('New Data Added Successfully.');
            // If all queries succeed, commit the transaction
            DB::commit();
        }catch (\Exception $e) {
            // If any query fails, catch the exception and roll back the transaction
            dd($e);
            exit();
            flash()->addError('Data Not Added Successfully.');
            DB::rollback();
        }
        return redirect('customer');
    }

    public function edit_customer($id){
        $customer = Customer::findOrFail($id);
        // $vTrnx = AccountTranx::where('ref_id', $id)
        //                         ->where('ref_type', 'customer')
        //                         ->where('note', 'Customer Opening Due Balance')
        //                         ->get();
        
        return view('admin.customer.edit', compact('customer'));
    }
    
    public function update_customer(Request $request, $id){
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits:13']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('customer');
        }
        
        DB::beginTransaction();
        try{
            $data = Customer::findOrFail($id);
            
            $input = $request->all();
            if($data->opening_balance != $input['opening_balance'])
                $input['balance'] = $data->balance - ($data->opening_balance - $input['opening_balance']);
            else
                $input['balance'] = $data->balance;
            
            $data->update($input);

            DB::commit();
            // If all queries succeed, commit the transaction
            flash()->addSuccess('Data Update Successfully.');
        }catch (\Exception $e) {
            // If any query fails, catch the exception and roll back the transaction
            flash()->addError('Data Not Updated Successfully.');
            DB::rollback();
        }
        return redirect('customer');
    }

    public function delete_customer($id){
        $data = Customer::findOrFail($id);
        $data->is_delete = 1;
        $data->save();
        flash()->addSuccess('Data Delete Successfully.');
        return redirect('customer');
    }

    public function see_customer($id){
        $banks = Bankacc::get();
        $accounts_payable_bid = 0;
        $cash_bid = 0;
        foreach($banks as $r){
            if($r->type == 'Due' && $r->name == 'Due')
                $accounts_payable_bid = $r->id;
            if($r->type == 'Cash' && $r->name == 'Cash')
                $cash_bid = $r->id;
        }
        $customer = Customer::where('id', $id)
                            ->select('customers.*')
                            ->addSelect(DB::raw('(COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE account_id = "'.$cash_bid.'" AND ref_id = customers.id AND ref_type = "customer"), 0) - COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE account_id = "'.$accounts_payable_bid.'" AND ref_id = customers.id AND ref_type = "customer"), 0)) as due'))
                            ->get();
        $cs = Sales::where('customer_id', $id)
                    ->where('order_type', 'sales')
                    ->get();
        $quantity = 0;
        for($i=0; $i<count($cs); $i++){
            $q = json_decode($cs[$i]->products);
            for($j=0; $j<count($q); $j++)
                $quantity += $q[$j]->quantity;
        }
        // dd($quantity);

        if(! empty(request()->input('search'))){
            $str = request()->input('search');
            $datas = AccountTranx::join('bankaccs', 'account_tranxes.account_id', '=', 'bankaccs.id')
                            ->where(function ($query) use ($str){
                                $query->where('tranx_date', 'like', '%'.$str.'%')
                                ->orWhere('amount', 'like', '%'.$str.'%')
                                ->orWhere('bankaccs.name', 'like', '%'.$str.'%')
                                ->orWhere('note', 'like', '%'.$str.'%');
                            })
                            ->where('ref_id', $id)
                            ->where('ref_type', 'customer')
                            ->select('account_tranxes.*', 'bankaccs.name as bank_name')
                            ->latest()
                            ->paginate(10)->withQueryString();
        }else{
            $datas = AccountTranx::join('bankaccs', 'account_tranxes.account_id', '=', 'bankaccs.id')
                    ->where('ref_id', $id)
                    ->where('ref_type', 'customer')
                    ->select('account_tranxes.*', 'bankaccs.name as bank_name')
                    ->latest()
                    ->paginate(10)->withQueryString();
        }
        $balancesBefore = array();
        $aidcash = 0;
        $aiddue = 0;
        $c = 0;
        $d = 0;
        foreach($banks as $bank){
            if($bank->type == 'Cash') 
                $aidcash = $bank->id;
            elseif($bank->type == 'Due' && $bank->name == 'Due') 
                $aiddue = $bank->id;
        }
        if(isset($_GET['page']) && $_GET['page']>1){
            $balancesBefore = AccountTranx::where('ref_id', $id)
                            ->where('ref_type', 'customer')
                            ->where('id', '<', $datas->first()->id)
                            ->groupBy('account_id')
                            ->select('account_id', DB::raw('sum(amount) as total_amount')) // Select account_id and sum amount
                            ->get()->toArray();
            foreach($balancesBefore as $bf){
                if($bf['account_id'] == $aidcash) $c+=$bf["total_amount"];
                elseif($bf['account_id'] == $aiddue) $d+=$bf["total_amount"];
            }
        }
        foreach($datas as $row){
            if($aidcash == $row->account_id){
                $c+=$row->amount;
            }
            elseif($aiddue == $row->account_id){
                $d+=$row->amount;
            }
        }
        $table = ["balancesBefore"=>$balancesBefore, "datas"=>$datas, "d"=>$d, "c"=>$c, "aidcash"=>$aidcash, "aiddue"=>$aiddue];
        // dd($table);
        return view('admin.customer.details', compact('customer', 'quantity', 'table'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function add_amount(Request $request){
        $input = $request->all();
        if($input['amount']) $input['amount'] = b2en($input['amount']);
        $banks = Bankacc::get();
        $aid = 0;
        if($request->input('tranx_type') == 'debit'){
            foreach($banks as $r){
                if($r->type == 'Cash')
                    $aid = $r->id;
            }
        }elseif($request->input('tranx_type') == 'credit'){
            foreach($banks as $r){
                if($r->type == 'Due' && $r->name == 'Due')
                    $aid = $r->id;
            }
        }
        $input['account_id'] = $aid;
        $rules = [
            'tranx_date' => ['required', 'date'],
            'amount' => ['required']
        ];
        // $id = $request->input('account_id');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect($request->input('redirect_url'));
        }

        DB::beginTransaction();
        try{
            $customer = Customer::findOrFail($request->input('ref_id'));
            // if($customer){
            //     $customer->balance = $customer->balance - $request->input('amount');
            //     $customer->save();
            // }
    
            if(!empty($request->input('id'))){
                $data = AccountTranx::where('id', $request->input('id'))
                                    ->where('ref_id', $request->input('ref_id'))
                                    ->where('ref_type', $request->input('ref_type'))
                                    ->get();
                
                $input['account_id'] = $request->input('account_id');
                $input['user_id'] = Auth::id();
                $input['note'] = $input['note'] . ' (Edited)';
                $data[0]->fill($input)->save();
            }else{
                $data = new AccountTranx();
                $input['account_id'] = $aid;
                $input['user_id'] = Auth::id();
                
                $data->fill($input)->save();
            }
            

            if($request->input('sms_flag') == 'yes'){
                $sms = new SendSms();
                $sms->toSms($customer->mobile, config('app.name'). ' receive your payment BDT '. $input['amount'] .'. Thank you');
            }

            if(!empty($request->input('id')))
                flash()->addSuccess('Data Update Successfully.');
            else
                flash()->addSuccess('New Data Added Successfully.');
            // If all queries succeed, commit the transaction
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            // If any query fails, catch the exception and roll back the transaction
            flash()->addError('Data Not Added or Update Successfully.');
            DB::rollback();
        }
        return redirect($request->input('redirect_url'));
    }

    public function customer_tnx_edit($id){
        $order = AccountTranx::findOrFail($id);
        $account = Bankacc::all();
        return view('admin.customer.register_edit', compact('order', 'account'));
    }
    
    public function customer_tnx_delete($id){
        try{
            DB::beginTransaction();
            AccountTranx::where('id', $id)->delete();
            flash()->addSuccess('Customer Transection Deleted Successfully.');
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            flash()->addError('Customer Transection Unable To Delete');
            DB::rollback();
            return redirect('customer');
        }
        
        return redirect("customer");
    }
}
