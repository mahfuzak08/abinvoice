<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTranx;
use App\Models\Bankacc;
use App\Models\Vendor;
use App\Models\Purchase;

class VendorController extends Controller
{
    public function index(){
        if(! empty(request()->input('search'))){
            $str = request()->input('search');
            $datas = Vendor::select('vendors.*')
                            ->addSelect(DB::raw('(COALESCE((SELECT SUM(total_due) FROM purchases WHERE vendor_id = vendors.id AND status = 1), 0) + COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = vendors.id AND ref_type = "vendor" AND ref_tranx_id = "0"), 0)) as due'))
                            ->where(function ($query) use ($str){
                                $query->where('name', 'like', '%'.$str.'%')
                                ->orWhere('mobile', 'like', '%'.$str.'%')
                                ->orWhere('email', 'like', '%'.$str.'%')
                                ->orWhere('address', 'like', '%'.$str.'%');
                            })
                            ->where('is_delete', 0)
                            ->latest()->paginate(10)->withQueryString();
        }else{
            $datas = Vendor::select('vendors.*')
                            ->addSelect(DB::raw('(COALESCE((SELECT SUM(total_due) FROM purchases WHERE vendor_id = vendors.id AND status = 1), 0) + COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = vendors.id AND ref_type = "vendor" AND ref_tranx_id = "0"), 0)) as due'))
                            ->latest()
                            ->where('is_delete', 0)
                            ->paginate(10)
                            ->withQueryString();
        }
        return view('admin.vendor.manage', compact('datas'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function open_vendor_form(){
        return view('admin.vendor.addnew');
    }

    public function set_vendor(Request $request){
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits:13']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('add_new_vendor');
        }
        
        DB::beginTransaction();
        try{
            $data = new Vendor();
            
            $input = $request->all();
            $input['balance'] = empty($input['balance']) || $input['balance'] == null ? 0 : $input['balance'];
            $input['opening_balance'] = $input['balance'];
            $data->fill($input)->save();
    
            $vendor_id = $data->id;
            // $due_acc_id = Bankacc::where('type', 'Cash')->pluck('id');
            // $trnxdata = [
            //     'account_id' => $due_acc_id[0],
            //     'user_id' => Auth::id(),
            //     'tranx_date' => date("Y-m-d"),
            //     'ref_id' => $vendor_id,
            //     'ref_type' => 'vendor',
            //     'note' => 'Vendor Opening Due Balance',
            //     'amount' => !empty($request->input('balance')) && $request->input('balance')>0 ? ($request->input('balance') * -1) : 0
            // ];
            // $tdata = new AccountTranx();
            // $tdata->fill($trnxdata)->save();
            
            flash()->addSuccess('New Data Added Successfully.');
            // If all queries succeed, commit the transaction
            DB::commit();
        }catch (\Exception $e) {
            // If any query fails, catch the exception and roll back the transaction
            flash()->addError('Data Not Added Successfully.');
            DB::rollback();
        }
        return redirect('vendor');
    }

    public function edit_vendor($id){
        $vendor = Vendor::findOrFail($id);
        // $vTrnx = AccountTranx::where('ref_id', $id)
        //                         ->where('ref_type', 'vendor')
        //                         ->where('note', 'Vendor Opening Due Balance')
        //                         ->get();
        return view('admin.vendor.edit', compact('vendor'));
    }
    
    public function update_vendor(Request $request, $id){
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits:13']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('add_new_vendor');
        }
        
        DB::beginTransaction();
        try{
            $data = Vendor::findOrFail($id);
            
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
        return redirect('vendor');
    }

    public function delete_vendor($id){
        $data = Vendor::findOrFail($id);
        $data->is_delete = 1;
        DB::beginTransaction();
        try{
            $data->save();
            
            AccountTranx::where('ref_id', $id)->where('ref_type', 'vendor')->delete();

            DB::commit();
            // If all queries succeed, commit the transaction
            flash()->addSuccess('Data Delete Successfully.');
        }catch (\Exception $e) {
            // If any query fails, catch the exception and roll back the transaction
            flash()->addError('Data Not Delete Successfully.');
            DB::rollback();
        }
        return redirect('vendor');
    }

    public function see_vendor($id){
        $vendor = Vendor::where('id', $id)
                        ->select('vendors.*')
                        ->addSelect(DB::raw('(COALESCE((SELECT SUM(total_due) FROM purchases WHERE vendor_id = vendors.id AND status = 1), 0) + COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = vendors.id AND ref_type = "vendor" AND ref_tranx_id = "0"), 0)) as total_due'))
                        ->addSelect(DB::raw('(COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = vendors.id AND ref_type = "vendor"), 0)) as total_pay'))
                        // ->addSelect(DB::raw('(COALESCE((SELECT SUM(total_due) FROM purchases WHERE vendor_id = vendors.id AND status = 1 AND date >="'.$this->fysd.'" AND date <="'.$this->fyed.'"), 0) + COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = vendors.id AND ref_type = "vendor" AND ref_tranx_id = "0" AND tranx_date >="'.$this->fysd.'" AND tranx_date <="'.$this->fyed.'"), 0)) as cy_due'))
                        // ->addSelect(DB::raw('(COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = vendors.id AND ref_type = "vendor" AND tranx_date >="'.$this->fysd.'" AND tranx_date <="'.$this->fyed.'"), 0)) as cy_pay'))
                        ->get();
                        // dd($vendor);
        $banks = Bankacc::get();
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
                            ->where('ref_type', 'vendor')
                            ->select('account_tranxes.*', 'bankaccs.name as bank_name')
                            ->latest()
                            ->paginate(10)->withQueryString();
        }else{
            $datas = AccountTranx::join('bankaccs', 'account_tranxes.account_id', '=', 'bankaccs.id')
                    ->where('ref_id', $id)
                    ->where('ref_type', 'vendor')
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
            elseif($bank->type == 'Due' && $bank->name == 'Due2') 
                $aiddue = $bank->id;
        }
        if(isset($_GET['page']) && $_GET['page']>1){
            $balancesBefore = AccountTranx::where('ref_id', $id)
                            ->where('ref_type', 'vendor')
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
        return view('admin.vendor.details', compact('vendor', 'table'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function add_amount(Request $request){
        $input = $request->all();
        $banks = Bankacc::get();
        if($input['amount']) $input['amount'] = b2en($input['amount']);
        // dd($request->all());
        $aid = 0;
        if($request->input('tranx_type') == 'debit'){
            foreach($banks as $r){
                if($r->type == 'Cash')
                    $aid = $r->id;
            }
        }elseif($request->input('tranx_type') == 'credit'){
            foreach($banks as $r){
                if($r->type == 'Due' && $r->name == 'Due2')
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
            $vendor = Vendor::findOrFail($request->input('ref_id'));
            // if($vendor){
            //     $vendor->balance = $vendor->balance - $request->input('amount');
            //     $vendor->save();
            // }

            if(!empty($request->input('id'))){
                $data = AccountTranx::where('id', $request->input('id'))
                                    ->where('ref_id', $request->input('ref_id'))
                                    ->where('ref_type', $request->input('ref_type'))
                                    ->get();
                
                $input['user_id'] = Auth::id();
                $input['account_id'] = $request->input('account_id');
                $input['amount'] *= -1;
                $input['note'] = $input['note'] . ' (Edited)';
                // dd($input);
                $data[0]->fill($input)->save();
                flash()->addSuccess('Vendor Transection Update Successfully.');
            }else{
                $data = new AccountTranx();
                $input['user_id'] = Auth::id();
                $input['account_id'] = $aid;
                $input['amount'] *= -1;
                $data->fill($input)->save();
                flash()->addSuccess('Vendor Transection Added Successfully.');
            }
    
            // If all queries succeed, commit the transaction
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            // If any query fails, catch the exception and roll back the transaction
            flash()->addError('Vendor Transection Not Added/ Update Successfully.');
            DB::rollback();
        }
        return redirect($request->input('redirect_url'));
    }

    public function vendor_tnx_edit($id){
        $order = AccountTranx::findOrFail($id);
        $account = Bankacc::all();
        return view('admin.vendor.register_edit', compact('order', 'account'));
    }
    
    public function vendor_tnx_delete($id){
        try{
            DB::beginTransaction();
            AccountTranx::where('id', $id)->delete();
            flash()->addSuccess('Vendor Transection Deleted Successfully.');
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            flash()->addError('Vendor Transection Unable To Delete');
            DB::rollback();
            return redirect('vendor');
        }
        
        return redirect("vendor");
    }
}
