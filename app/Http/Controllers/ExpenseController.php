<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTranx;
use App\Models\Bankacc;
use App\Models\Expense;
use App\Models\Expense_detail;

class ExpenseController extends Controller
{
    public function index(){
        if(! empty(request()->input('search'))){
            $str = request()->input('search');
            $datas = Expense::where('name', 'like', '%'.$str.'%')
                            ->where('status', 1)
                            ->latest()->paginate(10)->withQueryString();
        }else{
            $datas = Expense::latest()->where('status', 1)->paginate(10)->withQueryString();
        }
        return view('admin.expense.manage', compact('datas'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function open_expense_form(){
        return view('admin.expense.addnew');
    }

    public function set_expense(Request $request){
        // print_r($request->all());
        $rules = [
            'name' => ['required', 'string', 'max:255']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('add_new_expense');
        }

        $data = new Expense();
        
        $input = $request->all();
        $input["created_by"] = Auth::id();

        $data->fill($input)->save();
        flash()->addSuccess('New Data Added Successfully.');
        return redirect('expense');
    }

    public function edit_expense($id){
        $expense = Expense::findOrFail($id);
        return view('admin.expense.edit', compact('expense'));
    }

    public function update_expense(Request $request, $id){
        $rules = [
            'name' => ['required', 'string', 'max:255']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('edit_expense', $id);
        }

        $data = Expense::findOrFail($id);
        
        $input = $request->all();
        
        $data->update($input);
        flash()->addSuccess('Data Updated Successfully.');
        return redirect('expense');
    }

    public function delete_expense($id){
        try{
            DB::beginTransaction();
            Expense_detail::where('expense_id', $id)->update(['status'=>0, 'details'=>"Deleted By ". Auth::user()->name . "( User Id: ". Auth::id() .")"]);
            
            AccountTranx::where('ref_id', $id)
                            ->where('ref_type', 'expense')
                            ->delete();
            
            $data = Expense::findOrFail($id);
            $data->fill(['status'=>0])->update();

            flash()->addSuccess('Expense Group Delete Successfully.');

            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            flash()->addError('Expense Group Unable To Delete');
            DB::rollback();
        }
        
        return redirect('expense');
    }

    public function expense_details($id){
        $expense = Expense::findOrFail($id);
        $banks = Bankacc::where('type', '!=', 'Due')->get();
        if(! empty(request()->input('search'))){
            $str = request()->input('search');
            $datas = Expense_detail::join('bankaccs', 'expense_details.account_id', '=', 'bankaccs.id')
                            ->where(function ($query) use ($str){
                                $query->where('trnx_date', 'like', '%'.$str.'%')
                                ->orWhere('amount', 'like', '%'.$str.'%')
                                ->orWhere('bankaccs.name', 'like', '%'.$str.'%')
                                ->orWhere('details', 'like', '%'.$str.'%');
                            })
                            ->where('expense_id', $id)
                            ->where('status', 1)
                            ->select('expense_details.*', 'bankaccs.name as bank_name')
                            ->latest()->paginate(10)->withQueryString();
        }else{
            $datas = Expense_detail::join('bankaccs', 'expense_details.account_id', '=', 'bankaccs.id')
                    ->where('expense_id', $id)
                    ->where('status', 1)
                    ->select('expense_details.*', 'bankaccs.name as bank_name')
                    ->latest()->paginate(10)->withQueryString();
        }
        if(! $datas->hasMorePages()){
            $etotal = Expense_detail::where('expense_id', $id)->where('status', 1)->sum('amount');
        }
        else{
            $etotal = 0;
        }
        return view('admin.expense.details', compact('expense', 'etotal', 'banks', 'datas'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function add_expense_amount(Request $request){
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
            return redirect($request->input('redirect_url'));
        }

        try{
            DB::beginTransaction();
            if(! empty($request->input("order_id"))){
                $order = Expense_detail::findOrFail($request->input("order_id"));
                
                $ex_input = [
                    "trnx_date"=> $request->input("tranx_date"),
                    "user_id"=> Auth::id(),
                    "account_id"=> $request->input("account_id"),
                    "amount"=> $request->input("amount"),
                    "title"=> $request->input("title"),
                    "details"=> $request->input("details") . "(Edited)"
                ];

                $order->fill($ex_input)->save();

                AccountTranx::where('ref_tranx_id', $order->id)
                            ->where('ref_tranx_type', 'expense_order')
                            ->delete();

                $data = new AccountTranx();
    
                $input = $request->all();
                $input['user_id'] = Auth::id();
                $input['amount'] *= -1;
                $input['ref_tranx_id'] = $order->id;
                $input['ref_tranx_type'] = "expense_order";
                $input['note'] = "This tranx updated.";
                
                $data->fill($input)->save();
                
                flash()->addSuccess('Expense Data Update Successfully.');
                // If all queries succeed, commit the transaction
                DB::commit();
            }else{
                $ex_order = new Expense_detail();
                $ex_input = [
                    "trnx_date"=> $request->input("tranx_date"),
                    "expense_id"=> $request->input("ref_id"),
                    "user_id"=> Auth::id(),
                    "account_id"=> $request->input("account_id"),
                    "amount"=> $request->input("amount"),
                    "title"=> $request->input("title"),
                    "details"=> $request->input("details")
                ];

                $ex_order->fill($ex_input)->save();

                $data = new AccountTranx();
                
                $input = $request->all();
                $input['user_id'] = Auth::id();
                $input['amount'] *= -1;
                $input['ref_tranx_id'] = $ex_order->id;
                $input['ref_tranx_type'] = "expense_order";
                
                $data->fill($input)->save();
                
                flash()->addSuccess('New Data Added Successfully.');
                // If all queries succeed, commit the transaction
                DB::commit();
            }
        }catch (\Exception $e) {
            dd($e);
            // If any query fails, catch the exception and roll back the transaction
            flash()->addError('Data Not Added/ Updated Successfully.');
            DB::rollback();
        }
        return redirect($request->input('redirect_url'));
    }

    public function invoice($id){
        $invoice = Expense_detail::join("expenses", "expense_details.expense_id", "=", "expenses.id")
                                ->join("bankaccs", "expense_details.account_id", "=", "bankaccs.id")
                                ->select("expense_details.*", "expenses.name as expense_name", "bankaccs.name as acc_name")
                                ->where("expense_details.id", $id)
                                ->get();
        return view('admin.expense.invoice', compact('invoice'));
    }

    public function expense_edit($id){
        $order = Expense_detail::join("expenses", "expense_details.expense_id", "=", "expenses.id")
                            ->select('expense_details.*', 'expenses.name as expense_name')
                            ->where("expense_details.id", $id)
                            ->get();
        $account = Bankacc::all();
        
        return view('admin.expense.register_edit', compact('order', 'account'));
    }
    
    public function expense_delete($id){
        try{
            DB::beginTransaction();
            $order = Expense_detail::findOrFail($id);
            $order->status = 0;
            $order->details = "Deleted By ". Auth::user()->name . "( User Id: ". Auth::id() .")";
            
            $order->save();

            AccountTranx::where('ref_tranx_id', $id)
                            ->where('amount', -1*$order->amount)
                            ->where('ref_tranx_type', 'expense_order')
                            ->delete();
            flash()->addSuccess('Expense Order Deleted Successfully.');
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            flash()->addError('Expense Order Unable To Delete');
            DB::rollback();
            return redirect('expense');
        }
        
        return redirect("expense");
    }

}
