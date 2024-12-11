<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTranx;
use App\Models\Bankacc;
use App\Models\Sales;
use App\Models\Customer;
use App\Models\Product_tranx;

class SalesController extends Controller
{
    public function index(){
        if(! hasModuleAccess("Sales"))
            return view('error403');
        $account = Bankacc::all();
        $customer = Customer::where('is_delete', 0)->get();

        if(hasModuleAccess("Inventory")){
            return view('admin.sales.register_with_inventory', compact('customer', 'account'));
        }else{
            if(! empty(request()->input('search'))){
                $str = request()->input('search');
                $datas = Customer::select('customers.*')
                                ->addSelect(DB::raw('(COALESCE((SELECT SUM(total_due) FROM sales WHERE customer_id = customers.id AND status = 1), 0) - COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = customers.id AND ref_type = "customer" AND ref_tranx_id = "0"), 0)) as due'))
                                ->where(function ($query) use ($str){
                                    $query->where('name', 'like', '%'.$str.'%')
                                    ->orWhere('mobile', 'like', '%'.$str.'%')
                                    ->orWhere('email', 'like', '%'.$str.'%')
                                    ->orWhere('address', 'like', '%'.$str.'%');
                                })
                                ->where('is_delete', 0)
                                ->latest()->paginate(10)->withQueryString();
            }else{
                $datas = Customer::select('customers.*')
                                ->addSelect(DB::raw('(COALESCE((SELECT SUM(total_due) FROM sales WHERE customer_id = customers.id AND status = 1), 0) - COALESCE((SELECT SUM(amount) FROM account_tranxes WHERE ref_id = customers.id AND ref_type = "customer" AND ref_tranx_id = "0"), 0)) as due'))
                                ->latest()
                                ->where('is_delete', 0)
                                ->paginate(10)
                                ->withQueryString();
            }
            return view('admin.sales.register', compact('customer', 'account', 'datas'))->with('i', (request()->input('page', 1) - 1) * 10);
        }
    }

    public function set_sales(Request $request){
        $rules = [
            'customer_new' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'total' => ['required', 'numeric']
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->messages()->toArray() as $key => $value) { 
                flash()->addError($value[0]);
            }
            return redirect('sales');
        }
        $customer_id = $request->input('customer_id');
        $due = 0;
        $discount=0;
        if($request->input('customer_id') == null){
            if($request->input('mobile') == null){
                flash()->addError('Mobile number is required.');
                return redirect('sales');
            }
            DB::beginTransaction();
            try{
                $cinfo = new Customer();
                
                $input = ["name"=>$request->input("customer_new"), "address"=>$request->input('address'), "mobile"=>$request->input('mobile')];
                
                $cinfo->fill($input)->save();
        
                $customer_id = $cinfo->id;
                
                flash()->addSuccess('New Customer Added Successfully.');
                // If all queries succeed, commit the transaction
                DB::commit();
            }catch (\Exception $e) {
                // If any query fails, catch the exception and roll back the transaction
                flash()->addError('Customer Not Added Successfully.');
                DB::rollback();
                return redirect('sales');
            }
        }else{
            $cinfo = Customer::findOrFail($customer_id);
            // $asofdue = $cinfo->balance;
        }
        
        $due_acc_id = Bankacc::where('type', 'Due')->pluck('id');
        $discount_acc_id = Bankacc::where('type', 'Discount')->pluck('id');
        
        $pn = $request->input('product_name');
        $pd = $request->input('product_details');
        $qty = $request->input('quantity');
        $price = $request->input('price');
        $pid = $request->input('product_id');
        $ptype = $request->input('payment_type');
        $receive_amount = $request->input('receive_amount');
        
        $items = array();
        $payments = array();
        $total = 0;
        
        for($i=0; $i<count($pn); $i++){
            $items[] = [
                "pid"=>$pid[$i],
                "product_name"=>$pn[$i], 
                "product_details"=>$pd[$i], 
                "quantity"=>(float) $qty[$i],
                "price"=>(float) $price[$i],
                "total"=>(float) $qty[$i] * (float) $price[$i] 
            ];
            $total += (float) $qty[$i] * (float) $price[$i];
        }
        $total_receive = 0;
        for($i=0; $i<count($ptype); $i++){
            $payments[] = [
                "pid"=>$ptype[$i],
                "receive_amount"=>(float) $receive_amount[$i]
            ];
            $total_receive += (float) $receive_amount[$i];
            if(count($due_acc_id) > 0 && $ptype[$i] == $due_acc_id[0])
                $due += (float) $receive_amount[$i];
            if(count($discount_acc_id) > 0 && $ptype[$i] == $discount_acc_id[0])
                $discount += (float) $receive_amount[$i];
        }

        if((float)$total_receive < (float)$request->input('total') && $discount == 0){
            $discount = (float) $request->input('total') - (float) $total_receive;
            $payments[] = ["pid"=>$discount_acc_id[0], "receive_amount"=> $discount];
        }

        if(! empty($request->input('order_id')) && $request->input('order_id') > 0){
            $input_order = [
                "order_type"=> "sales",
                "user_id"=> Auth::id(),
                "customer_id"=> $customer_id,
                "products"=> json_encode($items),
                "date"=> $request->input('date'),
                "discount"=> $discount,
                "total"=> $total,
                "total_due"=> $due,
                "payment"=> json_encode($payments)
            ];

            try{
                DB::beginTransaction();
    
                $order = Sales::findOrFail($request->input('order_id'));
                $op = json_decode($order->payment);
                $input_order["note"] = json_encode(array("old_date"=>$order->date, "old_customer_id"=>$order->customer_id, "old_products"=>$order->products, "old_payment"=>$order->payment, "old_discount"=>$order->discount));

                $order->fill($input_order)->save();
                $order_id = $request->input('order_id'); 
                
                // for($pi = 0; $pi < count($op); $pi++){
                //     if(count($due_acc_id) > 0 && $op[$pi]->pid == $due_acc_id[0])
                //         $asofdue -= (float) $op[$pi]->receive_amount;
                // }
                // $cinfo->balance = $asofdue;
                // $cinfo->save();
    
                for($i=0; $i<count($ptype); $i++){
                    if(count($due_acc_id) > 0 && $ptype[$i] == $due_acc_id[0]) continue;
                    $tdata = AccountTranx::where('ref_tranx_id', $order_id)
                            ->where('ref_tranx_type', 'sales_order')
                            ->where('ref_type', 'customer')
                            ->where('account_id', $ptype[$i])
                            ->get()->toArray();
                    if(count($tdata) > 0){
                        $trnxdata = [
                            'user_id' => Auth::id(),
                            'tranx_date' => $request->input('date'),
                            'ref_id' => $customer_id,
                            'amount' => (float) $receive_amount[$i],
                            'note' => 'This tranx updated. Old amount was '.$tdata[0]['amount']
                        ];
                        AccountTranx::where('id', $tdata[0]['id'])->update($trnxdata);
                    }else{
                        $trnxdata = [
                            'account_id' => $ptype[$i],
                            'user_id' => Auth::id(),
                            'tranx_date' => $request->input('date'),
                            'ref_id' => $customer_id,
                            'ref_type' => 'customer',
                            'ref_tranx_id' => $order_id,
                            'ref_tranx_type' => 'sales_order',
                            'note' => 'This tranx updated.',
                            'amount' => (float) $receive_amount[$i]
                        ];
                        $tdata = new AccountTranx();
                        $tdata->fill($trnxdata)->save();
                    }
                }

                AccountTranx::where('ref_tranx_id', $order_id)->where('note', '')->delete();
    
                flash()->addSuccess('Sales Order Updated Successfully.');
                DB::commit();
            }catch (\Exception $e) {
                // If any query fails, catch the exception and roll back the transaction
                dd($e);
                flash()->addError('Sales Order Not Updated Successfully.');
                DB::rollback();
                return redirect('sales');
            }
            return redirect("sales_invoice/$order_id");
        }
        else{
            $moid = Sales::max("order_id");
        
            $input_order = [
                "order_id"=> $moid > 0 ? $moid+1 : 786,
                "order_type"=> "sales",
                "user_id"=> Auth::id(),
                "customer_id"=> $customer_id,
                "products"=> json_encode($items),
                "date"=> $request->input('date'),
                "discount"=> $discount,
                "total"=> $total,
                "payment"=> json_encode($payments),
                "total_due"=> $due,
            ];
            
            try{
                DB::beginTransaction();
                
                $order = New Sales();
                $order->fill($input_order)->save();
                $order_id = $order->id; 
                
                // $cinfo->balance = $asofdue;
                // $cinfo->save();

                for($i=0; $i<count($ptype); $i++){
                    if(count($due_acc_id) > 0 && $ptype[$i] == $due_acc_id[0]) continue;

                    $trnxdata = [
                        'account_id' => $ptype[$i],
                        'user_id' => Auth::id(),
                        'tranx_date' => $request->input('date'),
                        'ref_id' => $customer_id,
                        'ref_type' => 'customer',
                        'ref_tranx_id' => $order_id,
                        'ref_tranx_type' => 'sales_order',
                        'note' => '',
                        'amount' => (float) $receive_amount[$i]
                    ];
                    $tdata = new AccountTranx();
                    $tdata->fill($trnxdata)->save();
                }

                if(hasModuleAccess("Inventory")){
                    $ptdata = array();
                    for($i=0; $i<count($pn); $i++){
                        $pidvid = explode("@", $pid[$i]);
                        $ptdata[] = array(
                            "product_id"=>$pidvid[0],
                            "variant_id"=>$pidvid[1],
                            "order_id"=>$order_id, 
                            "order_type"=>'sales', 
                            "date"=>$request->input('date'),
                            "inout"=>'out',
                            "qty"=>(float) $qty[$i] * -1, 
                            "actual_sell_price"=>(float) $price[$i], 
                        );
                    }
                    Product_tranx::insert($ptdata);
                }

                flash()->addSuccess('Sales Order Added Successfully.');
                DB::commit();
            }catch (\Exception $e) {
                // If any query fails, catch the exception and roll back the transaction
                dd($e);
                flash()->addError('Sales Order Not Added Successfully.');
                DB::rollback();
                return redirect('sales');
            }
        }
        
        return redirect("sales_invoice/$order_id");
    }

    public function invoice($id){
        $invoice = Sales::join("customers", "sales.customer_id", "=", "customers.id")
                            ->select('sales.*', 'customers.name as customer_name', 'customers.mobile', 'customers.address')
                            ->where("sales.id", $id)
                            ->get();
        $account = Bankacc::all();
        
        return view('admin.sales.invoice', compact('invoice', 'account'));
    }

    public function sales_edit($id){
        $order = Sales::join("customers", "sales.customer_id", "=", "customers.id")
                            ->select('sales.*', 'customers.name as customer_name', 'customers.mobile', 'customers.address')
                            ->where("sales.id", $id)
                            ->get();
        $account = Bankacc::all();
        $customer = Customer::where('is_delete', 0)->get();
        
        return view('admin.sales.register_edit', compact('order', 'account', 'customer'));
    }
    
    public function sales_delete($id){
        try{
            DB::beginTransaction();
            $order = Sales::findOrFail($id);
            $order->order_type = "sales_delete";
            $order->status = 0;
            $order->note = "Deleted By ". Auth::user()->name . "( User Id: ". Auth::id() .")";
            
            $ptype = json_decode($order->payment);

            $order->save();

            $due_acc_id = Bankacc::where('type', 'Due')->pluck('id');
            
            for($i=0; $i<count($ptype); $i++){
                if(count($due_acc_id) > 0 && $ptype[$i]->pid == $due_acc_id[0]){
                    $v = Customer::findOrFail($order->customer_id); 
                    $v->balance -= $ptype[$i]->receive_amount;
                    $v->save();
                }
            }

            AccountTranx::where('ref_tranx_id', $id)->delete();
            flash()->addSuccess('Sales Order Deleted Successfully.');
            DB::commit();
        }catch (\Exception $e) {
            dd($e);
            flash()->addError('Sales Order Unable To Delete');
            DB::rollback();
            return redirect('sales');
        }
        
        return redirect("reportSales");
    }

}
